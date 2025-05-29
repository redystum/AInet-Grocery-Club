<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Card;

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
            $card = Card::firstOrCreate(['id' => $user->id]);
            // Ensure custom field is an array
            if (!is_array($card->custom)) {
                $card->custom = [];
            }

            // If there was a guest cart in session, merge it with the user's cart
            if (session()->has('guest_cart')) {
                $guestCart = session('guest_cart', []);
                $userCart = $card->custom;

                // Merge guest cart items into user cart
                foreach ($guestCart as $productId => $quantity) {
                    if (isset($userCart[$productId])) {
                        $userCart[$productId] += $quantity;
                    } else {
                        $userCart[$productId] = $quantity;
                    }
                }

                $card->custom = $userCart;
                $card->save();

                // Clear the guest cart from session
                session()->forget('guest_cart');
            }

            return $card;
        } else {
            // For guest users, use session instead
            if (!session()->has('guest_cart')) {
                session(['guest_cart' => []]);
            }

            // Create a virtual card object to maintain consistency
            $card = new Card();
            $card->custom = session('guest_cart', []);
            return $card;
        }
    }

    private function refreshCart()
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
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
        $card = $this->getCard();
        $cart = $card->custom ?? [];

        // Validate quantity
        $newQuantity = max(0, intval($newQuantity));
        $product = Product::find($productId);

        if ($product) {
            // Ensure quantity doesn't exceed stock
            $newQuantity = min($newQuantity, $product->stock);

            if ($newQuantity === 0) {
                // Remove item if quantity is zero
                unset($cart[$productId]);
            } else {
                // Update quantity
                $cart[$productId] = $newQuantity;
            }

            if (auth()->check()) {
                $card->custom = $cart;
                $card->save();
            } else {
                session(['guest_cart' => $cart]);
            }

            $this->refreshCart();
        }
    }

    public function increment($productId)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
        $product = Product::find($productId);

        if ($product && isset($cart[$productId])) {
            // Don't exceed stock
            if ($cart[$productId] < $product->stock) {
                $cart[$productId]++;

                if (auth()->check()) {
                    $card->custom = $cart;
                    $card->save();
                } else {
                    session(['guest_cart' => $cart]);
                }

                $this->refreshCart();
            }
        }
    }

    public function decrement($productId)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];

        if (isset($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                // Remove if quantity reaches zero
                unset($cart[$productId]);
            }

            if (auth()->check()) {
                $card->custom = $cart;
                $card->save();
            } else {
                session(['guest_cart' => $cart]);
            }

            $this->refreshCart();
        }
    }

    public function removeItem($productId)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);

            if (auth()->check()) {
                $card->custom = $cart;
                $card->save();
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
