<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CartOffcanvas extends Component
{
    public $open = false;
    public $cartItems = [];
    public $totalAmount = 0;

    protected $listeners = ['openCart', 'closeCart', 'updateCartItem', 'removeCartItem'];

    public function mount()
    {
        $this->loadCartItems();
    }

    private function loadCartItems()
    {
        // Read the cart format from session or user.custom
        $cart = auth()->check()
            ? auth()->user()->custom ?? []
            : session('guest_cart', []);

        $this->cartItems = [];

        // The cart format in controller is [product_id => quantity]
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                // Calculate discount if applicable
                $discountedPrice = $product->price;
                $discountPercentage = 0;

                if ($product->discount > 0) {
                    $discountPercentage = $product->discount;
                    $discountedPrice = round($product->price * (1 - $product->discount / 100), 2);
                }

                $this->cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'discounted_price' => $discountedPrice,
                    'discount_percentage' => $discountPercentage,
                    'quantity' => $quantity,
                    'photo' => $product->getImage(),
                    'upper_limit' => $product->stock_upper_limit,
                ];
            }
        }

        $this->calculateTotal();
    }

    public function openCart()
    {
        // Refresh cart items when opening to ensure data is current
        $this->loadCartItems();
        $this->open = true;
    }

    public function closeCart()
    {
        $this->open = false;
    }

    // Sync cart changes to session/database
    private function syncCart()
    {
        $cart = [];
        foreach ($this->cartItems as $item) {
            $cart[$item['id']] = $item['quantity'];
        }

        if (auth()->check()) {
            $user = auth()->user();
            $user->custom = $cart;
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        $this->calculateTotal();
    }

    public function updateCartItem($itemId, $quantity)
    {
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $itemId) {
                // Respect the upper_limit if it exists
                if (isset($item['upper_limit'])) {
                    $quantity = min($quantity, $item['upper_limit']);
                }
                $this->cartItems[$index]['quantity'] = max(1, $quantity);
                break;
            }
        }

        $this->syncCart();
    }

    public function incrementQuantity($itemId)
    {
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $itemId) {
                if (!isset($item['upper_limit']) || $item['quantity'] < $item['upper_limit']) {
                    $this->cartItems[$index]['quantity']++;
                } else if (isset($item['upper_limit']) && $item['quantity'] > $item['upper_limit']) {
                    $this->cartItems[$index]['quantity'] = $item['upper_limit'];
                }
                break;
            }
        }

        $this->syncCart();
    }

    public function decrementQuantity($itemId)
    {
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $itemId) {
                if ($this->cartItems[$index]['quantity'] > 1) {
                    if (isset($item['upper_limit']) && $this->cartItems[$index]['quantity'] > $item['upper_limit']) {
                        $this->cartItems[$index]['quantity'] = max(1, $this->cartItems[$index]['quantity'] - 1);
                    } else {
                        $this->cartItems[$index]['quantity']--;
                    }
                }
                break;
            }
        }

        $this->syncCart();
    }

    public function removeCartItem($itemId)
    {
        $this->cartItems = array_filter($this->cartItems, function ($item) use ($itemId) {
            return $item['id'] != $itemId;
        });

        $this->syncCart();
    }

    private function calculateTotal()
    {
        $this->totalAmount = array_reduce($this->cartItems, function ($carry, $item) {
            // Use discounted price if available
            $price = isset($item['discounted_price']) ? $item['discounted_price'] : $item['price'];
            return $carry + ($price * $item['quantity']);
        }, 0);
    }

    public function getCartCount()
    {
        return array_sum(array_column($this->cartItems, 'quantity'));
    }

    public function render()
    {
        return view('livewire.cart-offcanvas');
    }
}