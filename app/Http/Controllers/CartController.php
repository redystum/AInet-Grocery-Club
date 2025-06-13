<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Card;

use App\Models\User;

class CartController extends Controller
{
    // Helper to get card data for both authenticated and guest users
    private function getCard()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Ensure the custom field is an array
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

    public function index()
    {
        // With Livewire, we just need to return the view
        // The Livewire component will handle loading and displaying cart items
        return view('pages.cart');
    }

    public function add($productId, Request $request)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];
        $quantity = intval($request->input('quantity', 1));
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        if (auth()->check()) {
            $user->custom = $cart;
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        $product = Product::find($productId);
        $productName = $product ? $product->name : 'Product';

        return response()->json([
            'success' => true,
            'message' => "$productName added to your cart",
            'quantity' => $quantity
        ]);
    }

    // API routes used by non-Livewire parts of the application

    public function changeQuantity($id, Request $request)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];
        $quantity = max(0, intval($request->input('quantity')));
        if (isset($cart[$id])) {
            // If quantity is zero, remove the item from cart
            if ($quantity === 0) {
                unset($cart[$id]);
            } else {
                $cart[$id] = $quantity;
            }

            if (auth()->check()) {
                $user->custom = $cart;
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
        $user = $this->getCard();
        $cart = [];
        foreach ($items as $item) {
            $cart[$item['id']] = max(1, intval($item['quantity']));
        }

        if (auth()->check()) {
            $user->custom = $cart;
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $user = $this->getCard();
        $cart = $user->custom ?? [];
        unset($cart[$id]);

        if (auth()->check()) {
            $user->custom = $cart;
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }
}
