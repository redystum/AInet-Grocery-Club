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
        $product = Product::findOrFail($request->input('product_id'));
        $cart = session()->get('cart', []);
        $qty = (int) $request->input('quantity', 1);

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