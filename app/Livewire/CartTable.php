<?php

namespace App\Livewire;

use App\Models\Card;
use App\Models\Product;
use Livewire\Component;

use App\Models\User;

class CartTable extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $discounts = 0;
    public $shipping = 0;
    public $total = 0;
    public $total_with_shipping = 0;

    public function mount()
    {
        $this->refreshCart();
    }

    private function getCard()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Ensure custom field is an array
            if (!is_array($user->custom)) {
                $user->custom = [];
            }

            // If there was a guest cart in session, merge it with the user's cart
            if (session()->has('guest_cart')) {
                $guestCart = session('guest_cart', []);
                $userCart = $user->custom;

                // Merge guest cart items into user cart
                foreach ($guestCart as $productId => $quantity) {
                    if (isset($userCart[$productId])) {
                        $userCart[$productId] += $quantity;
                    } else {
                        $userCart[$productId] = $quantity;
                    }
                }

                $user->custom = $userCart;
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
            $user->custom = session('guest_cart', []);
            return $user;
        }
    }

    private function refreshCart()
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];
        $this->cartItems = [];
        $this->subtotal = 0;
        $this->discounts = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('category')->find($productId);

            if (!$product) continue;

            $hasDiscount = ($product->discount > 0 && $product->discount_min_qty > 0);
            $isDiscounted = ($hasDiscount && $quantity >= $product->discount_min_qty);

            $unitPrice = $isDiscounted ? ($product->price - $product->discount) : $product->price;
            $lineDiscount = $isDiscounted ? $product->discount * $quantity : 0;
            $lineSubtotal = $product->price * $quantity;

            $this->cartItems[] = (object)[
                'id' => $productId,
                'product' => $product,
                'quantity' => $quantity,
                'total' => $unitPrice * $quantity,
                'line_discount' => $lineDiscount,
                'unit_price' => $unitPrice,
                'original_unit_price' => $product->price,
                'is_discounted' => $isDiscounted,
                'discount_percent' => $hasDiscount ? round(($product->discount / $product->price) * 100) : 0
            ];

            $this->subtotal += $lineSubtotal;
            $this->discounts += $lineDiscount;
        }

        $this->total = $this->subtotal - $this->discounts;

        // Calculate shipping
        if ($this->subtotal <= 50) {
            $this->shipping = 10;
        } elseif ($this->subtotal > 50 && $this->subtotal <= 100) {
            $this->shipping = 5;
        } else {
            $this->shipping = 0;
        }

        $this->total_with_shipping = $this->total + $this->shipping;
    }

    public function updateQuantity($productId, $newQuantity)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];

        // Validate quantity
        $newQuantity = max(0, intval($newQuantity));
        $product = Product::find($productId);

        if ($product) {
            // Ensure quantity doesn't exceed stock
            $newQuantity = min($newQuantity, $product->stock_upper_limit);

            if ($newQuantity === 0) {
                // Remove item if quantity is zero
                unset($cart[$productId]);
            } else {
                // Update quantity
                $cart[$productId] = $newQuantity;
            }

            if (auth()->check()) {
                $user->custom = $cart;
                $user->save();
            } else {
                session(['guest_cart' => $cart]);
            }

            $this->refreshCart();
        }
    }

    public function increment($productId)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];
        $product = Product::find($productId);

        if ($product && isset($cart[$productId])) {
            if ($cart[$productId] < $product->stock_upper_limit) {
                $cart[$productId]++;
            } else {
                // Set to upper limit if it would exceed
                $cart[$productId] = $product->stock_upper_limit;
            }

            if (auth()->check()) {
                $user->custom = $cart;
                $user->save();
            } else {
                session(['guest_cart' => $cart]);
            }

            $this->refreshCart();
        }
    }

    public function decrement($productId)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];
        $product = Product::find($productId);

        if (isset($cart[$productId]) && $product) {
            if ($cart[$productId] > 1) {
                if ($cart[$productId] > $product->stock_upper_limit) {
                    $cart[$productId] = $product->stock_upper_limit;
                } else {
                    $cart[$productId]--;
                }
            } else {
                // Remove if quantity reaches zero
                unset($cart[$productId]);
            }

            if (auth()->check()) {
                $user->custom = $cart;
                $user->save();
            } else {
                session(['guest_cart' => $cart]);
            }

            $this->refreshCart();
        }
    }

    public function removeItem($productId)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);

            if (auth()->check()) {
                $user->custom = $cart;
                $user->save();
            } else {
                session(['guest_cart' => $cart]);
            }

            $this->refreshCart();
        }
    }

    public function render()
    {
        return view('livewire.cart-table');
    }
}
