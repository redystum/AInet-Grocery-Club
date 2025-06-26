<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\ItemsOrder;
use App\Models\Operations;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingCosts;
use App\Models\User;
use App\Notifications\OrderPlaced;
use App\Utils\CustomFieldManager;
use DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Helper to get card data for both authenticated and guest users
    private function getCart()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Ensure the custom field is an array
            if (!is_array($user->custom)) {
                CustomFieldManager::update_or_create_array($user->custom, ['card' => []]);
            }

            // If there was a guest cart in session, merge it with the user's cart
            if (session()->has('guest_cart')) {
                $guestCart = session('guest_cart', []);
                $userCart = CustomFieldManager::get_field($user, 'card') ?? [];

                // Merge guest cart items into user cart
                foreach ($guestCart as $productId => $quantity) {
                    if (isset($userCart[$productId])) {
                        $userCart[$productId] += $quantity;
                    } else {
                        $userCart[$productId] = $quantity;
                    }
                }

                $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $userCart]);
                $user->save();

                // Clear the guest cart from session
                session()->forget('guest_cart');
            }

            return $user;
        } else {
            // For guest users, use session instead
            if (!session()->has('guest_cart')) {
                session(['guest_cart' => []]);
            }

            // Create a virtual user object to maintain consistency
            $user = new User();
            $user->custom = ['card' => session('guest_cart', [])];
            return $user;
        }
    }

    // Public method to merge guest cart with user cart
    public function mergeGuestCartWithUserCart()
    {
        if (auth()->check() && session()->has('guest_cart')) {
            $user = auth()->user();
            $guestCart = session('guest_cart', []);
            $userCart = CustomFieldManager::get_field($user, 'card') ?? [];

            // Merge guest cart items into user cart
            foreach ($guestCart as $productId => $quantity) {
                if (isset($userCart[$productId])) {
                    $userCart[$productId] += $quantity;
                } else {
                    $userCart[$productId] = $quantity;
                }
            }

            $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $userCart]);
            $user->save();

            // Clear the guest cart from session
            session()->forget('guest_cart');

            return true;
        }

        return false;
    }

    public function index()
    {
        // With Livewire, we just need to return the view
        // The Livewire component will handle loading and displaying cart items
        return view('pages.cart');
    }

    public function add($productId, Request $request)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $quantity = intval($request->input('quantity', 1));

        // Check if the requested quantity exceeds available stock
        if ($quantity > $product->stock_upper_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add to cart. Maximum available stock is ' . $product->stock
            ]);
        }

        $user = $this->getCart();
        $cart = CustomFieldManager::get_field($user, 'card') ?? [];

        if (isset($cart[$productId])) {
            // Check if the current cart quantity plus new quantity exceeds stock
            if (($cart[$productId] + $quantity) > $product->stock_upper_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items. Maximum available stock is ' . $product->stock_upper_limit
                ]);
            }
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        if (auth()->check()) {
            $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $cart]);
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        $productName = $product->name;

        return response()->json([
            'success' => true,
            'message' => "$productName added to your cart",
            'quantity' => $quantity
        ]);
    }

    // API routes used by non-Livewire parts of the application

    public function changeQuantity($id, Request $request)
    {
        $user = $this->getCart();
        $cart = CustomFieldManager::get_field($user, 'card') ?? [];
        $quantity = max(0, intval($request->input('quantity')));
        if (isset($cart[$id])) {
            // If quantity is zero, remove the item from cart
            if ($quantity === 0) {
                unset($cart[$id]);
            } else {
                $cart[$id] = $quantity;
            }

            if (auth()->check()) {
                $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $cart]);
                $user->save();
            } else {
                session(['guest_cart' => $cart]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $items = $request->input('items', []);
        $user = $this->getCart();
        $cart = [];
        foreach ($items as $item) {
            $cart[$item['id']] = max(1, intval($item['quantity']));
        }

        if (auth()->check()) {
            $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $cart]);
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $user = $this->getCart();
        $cart = CustomFieldManager::get_field($user, 'card') ?? [];
        unset($cart[$id]);

        if (auth()->check()) {
            $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $cart]);
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nif' => 'required|string|size:9',
        ]);

        $user = $this->getCart();

        $cart = CustomFieldManager::get_field($user, 'card') ?? [];

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        return DB::transaction(function () use ($request, $user, $cart) {
            $total = 0;
            $delayed = false;
            $products = [];

            // Calculate total cost of products first
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if (!$product) {
                    return back()->with('error', 'Product not found.');
                }

                if ($quantity > $product->stock_upper_limit) {
                    return back()->with('error', 'Cannot add more items. Maximum available stock is ' . $product->stock_upper_limit);
                }

                if ($quantity >= $product->discount_min_qty) {
                    $total += $product->price * $quantity - $product->discount * $quantity;
                } else {
                    $total += $product->price * $quantity;
                }

            }

            // Calculate shipping cost
            $shipping = 0;
            $shippingRates = ShippingCosts::orderBy('min_value_threshold')->get();

            foreach ($shippingRates as $rate) {
                if ($total >= $rate->min_value_threshold && $total <= $rate->max_value_threshold) {
                    $shipping = $rate->shipping_cost;
                    break;
                }
            }

            $orderTotal = $total + $shipping;

            // Check if user has enough balance
            $card = Card::where('id', $user->id)->first();
            if (!$card || $card->balance < $orderTotal) {
                DB::rollBack();
                return back()->with('error', 'Insufficient funds to complete this purchase.');
            }

            $order = Order::create([
                'member_id' => $user->id,
                'status' => Order::STATUS_PENDING,
                'date' => now()->format('Y-m-d'),
                'total_items' => count($cart),
                'shipping_cost' => $shipping,
                'total' => $orderTotal,
                'nif' => $request->input('nif', ''),
                'delivery_address' => $user->default_delivery_address,
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);

                if ($quantity > $product->stock) {
                    $delayed = true;
                }

                ItemsOrder::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'discount' => $product->discount ?? 0,
                    'subtotal' => ($product->price - $product->discount) * $quantity,
                ]);

                $products[] = [
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                    'image' => $product->getImage(),
                ];
            }

            Card::where('id', $user->id)->decrement('balance', $orderTotal);

            Operations::create([
                'card_id' => $user->id,
                'type' => Operations::TYPE_DEBIT,
                'value' => $orderTotal,
                'date' => now()->format('Y-m-d'),
                'debit_type' => Operations::TYPE_DEBIT_ORDER,
                'credit_type' => null,
                'payment_type' => null,
                'payment_reference' => null,
                'order_id' => $order->id
            ]);

            // Clear the cart after order creation
            if (auth()->check()) {
                $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => []]);
                $user->save();
            } else {
                session(['guest_cart' => []]);
            }

            $user->notify(new OrderPlaced(
                $order->id,
                $order->date,
                $order->delivery_address,
                $products,
                $delayed
            ));

            return redirect()->route('after-purchase');
        });
    }
}