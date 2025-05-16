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
    public function index()
    {
        return view('pages.admin.stock.index');
    }

    public function restockAuto()
    {
        $products = Product::whereColumn('stock', '<=', 'stock_lower_limit')->get();
        $orderSupplies = SupplyOrder::where('status', 'requested')->whereIn('product_id', $products->pluck('id'))->get();
        $orderSuppliesIds = $orderSupplies->pluck('product_id')->toArray();

        $alreadyExists = false;

        foreach ($products as $product) {
            $restock = $product->stock_upper_limit - $product->stock;
            $product->setAttribute('restock', $restock);

            if ($orderSupplies->isNotEmpty()) {
                if (in_array($product->id, $orderSuppliesIds)) {
                    $product->setAttribute('alreadySupplyOrder', true);
                    $alreadyExists = true;
                }
            }
        }

        return view('pages.admin.stock.restock', compact('products', 'alreadyExists'));
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
            $random_date = now()->addDays(rand(2, 5));

            $custom = json_encode([
                'expected_delivery_date' => $random_date,
                'delivered_at' => null,
            ]);

            SupplyOrder::create([
                'product_id' => $productId,
                'registered_by_user_id' => auth()->id(),
                'status' => 'requested',
                'quantity' => $quantity,
                'custom' => $custom,
            ]);
        }

        return redirect()->route('board.stock')->with('toast', [
            'title' => 'Success',
            'message' => 'Supply order created successfully.',
            'type' => 'success',
        ]);
    }

    public function restock(Product $product)
    {
        if ($product->stock >= $product->stock_upper_limit) {
            return redirect()->route('board.stock')->with('toast', [
                'title' => 'Error',
                'message' => 'Product is full of stock.',
                'type' => 'error',
            ]);
        }

        $restock = $product->stock_upper_limit - $product->stock;
        $product->setAttribute('restock', $restock);

        $orderSupplies = SupplyOrder::where('status', 'requested')->where('product_id', $product->id)->first();
        $alreadyExists = false;

        if ($orderSupplies) {
            $alreadyExists = true;
            $product->setAttribute('alreadySupplyOrder', true);
        }

        $product->setAttribute('alreadyExists', $alreadyExists);

        $products = collect([$product]); // just to use the same page

        return view('pages.admin.stock.restock', compact('products', 'alreadyExists'));
    }
}
