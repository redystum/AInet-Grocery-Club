<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('products')->orderBy('created_at', 'desc')->paginate(5);
        foreach ($orders as $order) {
            $total_items = 0;
            $total_discount = 0; // in cents
            foreach ($order->items as $item) {
                $total_items += $item->quantity;
                $total_discount += $item->discount;
            }
            $order->items_count = $total_items;
            $order->total_discount = $total_discount;
        }

        return view('pages.user.orders', compact('orders'));
    }

    public function receipt(Order $order)
    {
        {
            if ($order->member_id != auth()->user()->id) {
                abort(404);
            }

            $path = storage_path('app/private/receipts/' . $order->pdf_receipt);
            if (!file_exists($path)) {
                abort(404);
            }

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $order->pdf_receipt . '"',
            ];
            return response()->file($path, $headers);

        }
    }
}
