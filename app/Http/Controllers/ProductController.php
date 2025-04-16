<?php
namespace App\Http\Controllers;

use App\Models\Product; // Certifique-se de ter o modelo Product
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Busca todos os produtos do banco de dados
        $products = Product::all();

        // Retorna a view com os produtos
        return view('pages.products', compact('products'));
    }
}