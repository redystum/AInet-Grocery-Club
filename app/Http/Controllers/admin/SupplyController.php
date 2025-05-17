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

    public function store(Request $request)
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

    public function edit()
    {
        return view('pages.admin.supply.edit');
    }

    public function cancel(SupplyOrder $order)
    {
        $otherOrders = SupplyOrder::whereDate('created_at', '=', $order->created_at->toDateString())
            ->where('id', '!=', $order->id)
            ->get();

        $otherOrders->prepend($order);
        return view('pages.admin.supply.cancel', compact('order', 'otherOrders'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'cancel_data' => 'required|array',
            'cancel_data.*' => 'exists:supply_orders,id',
            'reason' => 'required|string|max:255',
        ]);
        // reason is not used is just to simulate a real cancel...

        $orders = SupplyOrder::whereIn('id', $request->input('cancel_data'))->get();
        foreach ($orders as $order) {
            if ($order->status == SupplyOrder::STATUS_COMPLETED) {
                return redirect()->route('board.supply.index')
                    ->withErrors(['cancel_data' => 'Cannot cancel completed orders.']);
            }
            $order->delete();
        }

        return redirect()->route('board.supply.index')->with('toast', [
            'title' => 'Success',
            'message' => 'Supply orders deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        // TODO
    }


}
