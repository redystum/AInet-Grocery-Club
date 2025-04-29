<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogFilterRequest;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(CatalogFilterRequest $request)
    {
        $request->validated();

        $query = Product::query();

        $sort = $request->input('sort', 'discount_desc');

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('discount', 'desc');
                break;
        }

        if ($request->has('category')) {
            $category = $request->input('category');
            $query->where('category_id', $category);
        }

        $categories = Category::all();

        $products = $query->paginate(24);

        $totalProducts = Product::count();

        return view('pages.products', compact('categories', 'products', 'totalProducts'));
    }
}
