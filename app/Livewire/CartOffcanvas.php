<?php

namespace App\Livewire;

use App\Models\Product;
use App\Utils\CustomFieldManager;
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
            ? CustomFieldManager::get_field(auth()->user(), 'card') ?? []
            : session('guest_cart', []);

        $this->cartItems = [];

        // The cart format in controller is [product_id => quantity]
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                // Calculate discount if applicable
                $originalPrice = $product->price;
                $discountedPrice = $originalPrice;
                
                // Get the minimum quantity required for discount
                $minQuantityForDiscount = $product->discount_min_qty ?? 0;
                $hasDiscount = ($product->discount > 0 && $minQuantityForDiscount > 0);
                $discountApplied = ($hasDiscount && $quantity >= $minQuantityForDiscount);
                
                // Calculate discount percentage for display
                $discountPercentage = $hasDiscount ? round(($product->discount / $product->price) * 100) : 0;
                
                // Apply discount if conditions are met
                if ($discountApplied) {
                    $discountedPrice = $originalPrice - $product->discount;
                }

                $this->cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $originalPrice,
                    'discounted_price' => $discountedPrice,
                    'discount_percentage' => $discountPercentage,
                    'discount_applied' => $discountApplied,
                    'min_quantity_for_discount' => $minQuantityForDiscount,
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
    public function syncCart()
    {
        $cart = [];
        foreach ($this->cartItems as $item) {
            $cart[$item['id']] = $item['quantity'];
        }

        if (auth()->check()) {
            $user = auth()->user();
            // Fix: properly set the cart structure with CustomFieldManager
            $user->custom = CustomFieldManager::update_or_create_array($user->custom, ['card' => $cart]);
            $user->save();
        } else {
            session(['guest_cart' => $cart]);
        }

        // Reload items to recalculate discounts based on updated quantities
        $this->loadCartItems();
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
            // Use discounted price only if discount is actually applied
            $price = $item['discount_applied'] ? $item['discounted_price'] : $item['price'];
            return $carry + ($price * $item['quantity']);
        }, 0);
    }

    public function getCartCount()
    {
        return array_sum(array_column($this->cartItems, 'quantity'));
    }

    public function render()
    {
        $this->dispatch('cartUpdated');
        return view('livewire.cart-offcanvas');
    }
}