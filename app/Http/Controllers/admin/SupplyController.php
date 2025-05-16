<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        return view('pages.admin.supply.index');
    }

    public function store(Request $request){
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

    public function edit()
    {
        return view('pages.admin.supply.edit');
    }

    public function cancel($id)
    {
        return view('pages.admin.supply.cancel');
    }

    public function destroy($id)
    {
        // TODO
    }

    public function update(Request $request, $id)
    {
       // TODO
    }


}
