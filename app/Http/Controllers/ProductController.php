<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Inicializa a query para todos os produtos
        $query = Product::query();

        // Filtro de desconto (apenas se solicitado)
        if ($request->has('discount_only') && $request->discount_only) {
            $query->where('discount', '>', 0);
        }

        // Ordenação
        if ($request->has('sort')) {
            switch ($request->sort) {
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
                case 'discount_desc':
                    $query->orderBy('discount', 'desc');
                    break;
            }
        } else {
            // Ordenação padrão: maior desconto
            $query->orderBy('discount', 'desc');
        }

        // Busca todas as categorias
        $categories = Category::all();

        // Obtém os produtos filtrados
        $products = $query->get();

        return view('pages.products', compact('categories', 'products'));
    }

    public function category(Request $request, $categoryId)
    {
        // Busca a categoria específica
        $category = Category::with('products')->findOrFail($categoryId);

        // Ordenação
        $query = $category->products();
        if ($request->has('sort')) {
            switch ($request->sort) {
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
                case 'discount_desc':
                    $query->orderBy('discount', 'desc');
                    break;
            }
        }

        $products = $query->get();

        return view('pages.category', compact('category', 'products'));
    }
}
