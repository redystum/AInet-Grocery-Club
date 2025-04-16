<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        // Busca todas as categorias com os produtos relacionados
        $categories = Category::with('products')->get();

        return view('pages.products', compact('categories'));
    }
}