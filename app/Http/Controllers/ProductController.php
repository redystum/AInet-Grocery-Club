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

        return view('pages.product', compact('product', 'delivery_prices', 'random_products'));
    }

}
