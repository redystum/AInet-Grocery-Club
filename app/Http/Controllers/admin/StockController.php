<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockFilterRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(StockFilterRequest $request)
    {
        $categories = Category::all();

        if ($request->has('search')) {
            $products = Product::where('name', 'like', '%' . $request->input('search') . '%')
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('search') . '%');
                })
                ->orderBy($request->input('sort', 'stock'))
                ->paginate(100);
            return view('pages.admin.stock.index', compact('categories', 'products'));

        }


        $products = Product::query();

        if ($request->has('category')) {
            $products->where('category_id', $request->input('category'));
        }

        switch ($request->input('order_by', 'stock_low_high')) {
            case 'stock_high_low':
                $products->orderBy('stock', 'desc');
                break;
            case 'name_asc':
                $products->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $products->orderBy('name', 'desc');
                break;
            case 'category_asc':
                $products->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', 'asc');
                break;
            case 'category_desc':
                $products->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', 'desc');
                break;
            case 'price_high_low':
                $products->orderBy('price', 'desc');
                break;
            case 'price_low_high':
                $products->orderBy('price', 'asc');
                break;
            default: // stock_low_high
                $products->orderBy('stock');
                break;
        }

        if ($request->has('stock_status')) {
            if ($request->input('stock_status') === 'in_stock') {
                $products->whereColumn('stock', '>', 'stock_lower_limit');
            } elseif ($request->input('stock_status') === 'low_stock') {
                $products->whereColumn('stock', '<=', 'stock_lower_limit')
                    ->where('stock', '>', 0);
            } else {
                $products->where('stock', '=', 0);
            }
        }

        $products = $products->paginate(100);

        return view('pages.admin.stock.index', compact('categories', 'products'));
    }
}
