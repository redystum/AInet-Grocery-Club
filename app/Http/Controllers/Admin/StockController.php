<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustments;
use App\Models\SupplyOrder;
use App\Utils\ToastCreator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function store(StoreStockRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' .
                $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('products', $filename, 'public');
            $validated['photo'] = $filename;
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

    public function updateStock(Product $product, Request $request)
    {
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

    public function update(Product $product, UpdateStockRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' . $request['photo']->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $oldPhoto = $product->photo;
            if ($oldPhoto && Storage::disk('public')->exists('products/' . $oldPhoto)) {
                Storage::disk('public')->delete('products/' . $oldPhoto);
            }
            $validated['photo'] = $filename;
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

    public function delete(Product $product)
    {
        $product->delete();

        ToastCreator::success('Product deleted successfully.');
        return redirect()->route('board.stock');
    }
}