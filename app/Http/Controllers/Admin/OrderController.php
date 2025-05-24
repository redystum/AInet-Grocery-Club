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

        foreach ($order->items as $item) {
            $total_discount += $item->discount * $item->quantity;
        }

        $order->setAttribute("total_discount", $total_discount);

        CustomFieldManager::self_custom_to_attribute($order);

        return view('pages.admin.orders.show', compact('order'));

    }
}
