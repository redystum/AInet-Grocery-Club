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
        // With Livewire, we just need to return the view
        // The Livewire component will handle loading and displaying cart items
        return view('pages.cart');
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

    // API routes used by non-Livewire parts of the application

    public function changeQuantity($id, Request $request)
    {
        $card = $this->getCard();
        $cart = $card->custom ?? [];
        $quantity = max(0, intval($request->input('quantity')));
        if (isset($cart[$id])) {
            // Se quantidade for zero, remove o item do carrinho
            if ($quantity === 0) {
                unset($cart[$id]);
            } else {
                $cart[$id] = $quantity;
            }
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
