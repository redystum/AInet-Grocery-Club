<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Utils\CustomFieldManager;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'products']);

        $total_discount = 0; // in cents

        $can_be_delivered = true;
        $missing_products = [];

        foreach ($order->items as $item) {
            $total_discount += $item->discount * $item->quantity;
            if ($item->product->stock < $item->quantity) {
                $can_be_delivered = false;
                $missing_products[] = (object) [
                    'name' => $item->product->name,
                    'image' => $item->product->getImage(),
                    'id' => $item->product->id,
                    'missing_quantity' => $item->quantity - $item->product->stock,
                ];
            }
        }

        $order->setAttribute("total_discount", $total_discount);

        CustomFieldManager::self_custom_to_attribute($order);

        return view('pages.admin.orders.show', compact('order', 'can_be_delivered', 'missing_products'));

    }

    public function confirm(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->back()->with('toast', [
                    'title' => 'Error',
                    'message' => 'Insufficient stock for product: ' . $item->product->name,
                    'type' => 'error',
                ]);
            }
        }

        $order->status = Order::STATUS_COMPLETED;
        $order->save();

        foreach ($order->items as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        return redirect()->route('board.orders.index')->with('toast', [
            'title' => 'Success',
            'message' => 'Order marked as delivered successfully.',
            'type' => 'success',
        ]);
    }
}
