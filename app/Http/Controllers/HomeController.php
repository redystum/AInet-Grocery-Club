<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ItemsOrder;

class HomeController extends Controller
{
    public function index()
    {
        $topProducts = ItemsOrder::with('product')->groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->orderByDesc('total_quantity')
            ->take(4)
            ->get()
            ->map(function ($item) {
                return $item->product;
            });

        $topCategories = ItemsOrder::join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('products.category_id')
            ->selectRaw('products.category_id, SUM(items_orders.quantity) as total_quantity')
            ->orderByDesc('total_quantity')
            ->take(4)
            ->get()
            ->map(function ($item) {
                    return Category::find($item->category_id);
            });

        return view('pages.home', compact('topProducts', 'topCategories'));
    }
}
