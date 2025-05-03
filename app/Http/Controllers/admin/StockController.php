<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockFilterRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\SupplyOrder;
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

    public function restockAuto()
    {
        $products = Product::whereColumn('stock', '<=', 'stock_lower_limit')->get();

        foreach ($products as $product) {
            $restock = $product->stock_upper_limit - $product->stock;
            $product->setAttribute('restock', $restock);
        }

        return view('pages.admin.stock.restock', compact('products'));
    }

    public function restockConfirm(Request $request)
    {
        $request->validate([
            'restock_data' => 'required|json',
        ]);

        $restockData = json_decode($request->input('restock_data'), true);
        foreach ($restockData as $productId => $quantity) {
            if (!is_numeric($quantity)) {
                return redirect()->route('board.restock.auto')->withErrors(['restock_data' => 'Invalid restock data.']);
            }
            Product::findOrFail($productId);
        }

        foreach ($restockData as $productId => $quantity) {
            SupplyOrder::create([
                'product_id' => $productId,
                'registered_by_user_id' => auth()->id(),
                'status' => 'requested',
                'quantity' => $quantity,
                'custom' => null,
            ]);
        }

        return redirect()->route('board.stock')->with('toast',[
            'title' => 'Success',
            'message' => 'Supply order created successfully.',
            'type' => 'success',
        ]);
    }

    public function restock(Product $product)
    {
        if ($product->stock >= $product->stock_upper_limit) {
            return redirect()->route('board.stock')->withErrors(['product' => 'Product is already fully stocked.']);
        }

        $restock = $product->stock_upper_limit - $product->stock;
        $product->setAttribute('restock', $restock);

        $products = collect([$product]); // just to use the same page

        return view('pages.admin.stock.restock', compact('products'));
    }
}
