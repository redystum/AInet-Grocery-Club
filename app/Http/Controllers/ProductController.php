<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //Inicializa a query para os produtos
        $query = Product::query();

        //Filtro de busca por nome
        if($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por faixa de preço
        if ($request->has('price_range') && $request->price_range) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereBetween('price', [(float)$min, (float)$max]);
        }

        //busca todas as categorias com os produtos filtrados
        $categories = Category::with(['products' => function ($query) use ($request) {
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
        }])->get();

        //Obtem os produtos filtrados
        $products = $query->get();

        return view('pages.products', compact('categories', 'products'));
    }
}