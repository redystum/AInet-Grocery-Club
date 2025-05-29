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
        $user = auth()->user();
        $card = Card::firstOrCreate(['id' => $user->id]);
        // Ensure custom field is an array
        if (!is_array($card->custom)) {
            $card->custom = [];
        }
        return $card;
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

            $card->custom = $cart;
            $card->save();

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
                $card->custom = $cart;
                $card->save();
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
                $card->custom = $cart;
                $card->save();
            } else {
                // Remove if quantity reaches zero
                unset($cart[$productId]);
                $card->custom = $cart;
                $card->save();
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
            $card->custom = $cart;
            $card->save();
            $this->refreshCart();
        }
    }

    public function render()
    {
        return view('livewire.cart-table');
    }
}
