<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function show()
    {
        return view('pages.cart');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = session()->get('cart', []);
        $qty = (int) $validated['quantity'];

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'photo' => $product->photo,
                'price' => $product->price,
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);
        return back()->with('success', 'Product added to cart!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->input('product_id')]);
        session(['cart' => $cart]);
        return back()->with('success', 'Product removed from cart!');
    }
}