<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAdjustments;
use App\Models\SupplyOrder;
use App\Utils\ToastCreator;
use App\Models\Category;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return view('pages.admin.stock.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('pages.admin.stock.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'discount_min_qty' => 'nullable|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'stock_lower_limit' => 'nullable|integer|min:0',
            'stock_upper_limit' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
            $validated['photo'] = basename($validated['photo']);
        }

        Product::create($validated);

        ToastCreator::success('Product added successfully.');
        return redirect()->route('board.stock');
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

    public function restock(Product $product)
    {
        if ($product->stock >= $product->stock_upper_limit) {
            ToastCreator::error('Product is full of stock.');
            return redirect()->route('board.stock');
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

    public function update(Product $product, Request $request)
    {
        // If only updating stock quantity (from quick modal)
        if ($request->has('quantity') && count($request->all()) <= 3) {
            $request->validate([
                'quantity' => ['required', 'integer'],
            ]);

            if ($request->quantity > $product->stock_upper_limit) {
                ToastCreator::error('Cannot update stock to more than the upper limit.');
                return redirect()->route('board.stock');
            }

            if ($request->quantity < 0) {
                ToastCreator::error('Cannot update stock to less than 0.');
                return redirect()->route('board.stock');
            }

            $changedQuantity = $request->input('quantity') - $product->stock;

            $product->update(['stock' => $request->input('quantity')]);

            StockAdjustments::create([
                'product_id' => $product->id,
                'quantity_changed' => $changedQuantity,
                'registered_by_user_id' => auth()->id(),
            ]);

            ToastCreator::success('Stock updated successfully.');
            return redirect()->route('board.stock');
        }

        // Full product update (from edit form)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'discount_min_qty' => 'nullable|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'stock_lower_limit' => 'nullable|integer|min:0',
            'stock_upper_limit' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
            $validated['photo'] = basename($validated['photo']);
        }

        $product->update($validated);

        ToastCreator::success('Product updated successfully.');
        return redirect()->route('board.stock');
    }

    public function edit(Product $product)
    {
        $categories = \App\Models\Category::all();
        return view('pages.admin.stock.edit', compact('product', 'categories'));
    }
}