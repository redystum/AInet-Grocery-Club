<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Card;

class CartController extends Controller
{
    // Helper para obter (ou criar) o Card do user autenticado
    private function getCard()
    {
        $user = auth()->user();
        $card = Card::firstOrCreate(['id' => $user->id]);
        // Garante que o campo custom existe e é array
        if (!is_array($card->custom)) {
            $card->custom = [];
        }
        return $card;
    }

    public function index()
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
        $cartItems = [];
        $subtotal = 0;
        $discounts = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('category')->find($productId);

            if (!$product) continue;

            $hasDiscount = ($product->discount > 0 && $product->discount_min_qty > 0);
            $isDiscounted = ($hasDiscount && $quantity >= $product->discount_min_qty);

            $unitPrice = $isDiscounted ? ($product->price - $product->discount) : $product->price;
            $lineDiscount = $isDiscounted ? $product->discount * $quantity : 0;
            $lineSubtotal = $product->price * $quantity;

            $cartItems[] = (object)[
                'id' => $productId,
                'product' => $product,
                'quantity' => $quantity,
                'total' => $unitPrice * $quantity,
                'line_discount' => $lineDiscount,
                'unit_price' => $unitPrice,
                'original_unit_price' => $product->price,
            ];

            $subtotal  += $lineSubtotal;
            $discounts += $lineDiscount;
        }

        $total = $subtotal - $discounts;

        return view('pages.cart', compact('cartItems', 'subtotal', 'discounts', 'total'));
    }

    public function add($productId, Request $request)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
        $quantity = intval($request->input('quantity', 1));
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        $card->custom = $cart;
        $card->save();
        return redirect()->route('cart.index');
    }

    public function changeQuantity($id, Request $request)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
        $quantity = max(1, intval($request->input('quantity')));
        if (isset($cart[$id])) {
            $cart[$id] = $quantity;
            $card->custom = $cart;
            $card->save();
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $items = $request->input('items', []);
        $card = $this->getCard();
        $cart = [];
        foreach ($items as $item) {
            $cart[$item['id']] = max(1, intval($item['quantity']));
        }
        $card->custom = $cart;
        $card->save();
        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
        unset($cart[$id]);
        $card->custom = $cart;
        $card->save();
        return response()->json(['success' => true]);
    }
}
