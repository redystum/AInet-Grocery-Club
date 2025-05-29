<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = session('cart', []);
        return response()->json($cart);
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $qty = max(1, (int)$request->input('quantity', 1));
        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'photo' => $product->photo,
                'price' => $product->price,
                'discount' => $product->discount,
                'quantity' => $qty,
            ];
        }
        session(['cart' => $cart]);
        return response()->json($cart);
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->input('product_id')]);
        session(['cart' => $cart]);
        return response()->json($cart);
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        $id = $request->input('product_id');
        $qty = max(1, (int)$request->input('quantity', 1));
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $qty;
        }
        session(['cart' => $cart]);
        return response()->json($cart);
    }
}