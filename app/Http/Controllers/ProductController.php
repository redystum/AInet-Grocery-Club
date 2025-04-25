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
        $images = [$product->photo, '00015_fwX1WtXAEt.jpg', '00189_4b5cPthvBR.jpg', $product->photo];

        return view('pages.product', compact('product', 'delivery_prices', 'random_products', 'images'));
    }

}
