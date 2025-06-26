<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ItemsOrder;

class ProductStatsController extends Controller
{
    public function index()
    {
        // Total de produtos
        $totalProducts = Product::count();

        // Produto mais vendido
        $topProduct = ItemsOrder::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->first();

        $topProductName = $topProduct ? Product::find($topProduct->product_id)->name : 'N/A';
        $topProductSold = $topProduct ? $topProduct->total_sold : 0;

        // Stock médio
        $avgStock = round(Product::avg('stock'), 2);

        // Total vendido (todas as quantidades)
        $totalSold = ItemsOrder::sum('quantity');

        return view('pages.admin.products.stats', compact('totalProducts', 'topProductName', 'topProductSold', 'avgStock', 'totalSold'));
    }
}
