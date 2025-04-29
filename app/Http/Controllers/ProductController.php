<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShippingCosts;

class ProductController extends Controller
{

    public function show(Product $product)
    {

        $product->load('category');

        $delivery_prices = ShippingCosts::orderBy('min_value_threshold', 'asc')->get();

        $random_products = Product::where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // TODO: replce this with the custom column
        $images = [$product->photo, $random_products->first()->photo, $random_products->last()->photo, $product->photo];

        return view('pages.product', compact('product', 'delivery_prices', 'random_products', 'images'));
    }

}
