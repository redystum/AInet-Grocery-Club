<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Utils\CustomFieldManager;
use Illuminate\Http\Request;

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
                $missing_products[] = (object)[
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

    public function cancel(Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->back()->with('toast', [
                'title' => 'Error',
                'message' => 'Only pending orders can be canceled.',
                'type' => 'error',
            ]);
        }

        $total_items = 0;
        $total_discount = 0; // in cents

        foreach ($order->items as $item) {
            $total_items += $item->quantity;
            $total_discount += $item->discount * $item->quantity;
        }

        $order->setAttribute("items_count", $total_items);
        $order->setAttribute("total_discount", $total_discount);

        return view('pages.admin.orders.cancel', compact('order'));
    }

    public function cancelByAdmin(Order $order, Request $request)
    {
        if ($order->status != Order::STATUS_PENDING) {
            return redirect()->route('orders')->with('toast', [
                'title' => 'Error',
                'message' => 'You cannot cancel an order that is not pending',
                'type' => 'error',
            ]);
        }

        $request->validate([
            'reason' => 'required|int|in:0,1,2,3,4,5',
            'details' => 'nullable|string|max:255',
        ]);

        $reason = $request->input('reason');

        $reason_text = '';
        if ($reason == 5) {
            $request->validate([
                'details' => 'required|string|max:255',
            ]);
        }

        switch ($reason) {
            case 1:
                $reason_text = 'Excessive processing time';
                break;
            case 2:
                $reason_text = 'Contacted by the User';
                break;
            case 3:
                $reason_text = 'Product arrived damaged or defective';
                break;
            case 4:
                $reason_text = 'Company bankruptcy';
                break;
        }

        $order->update([
            'status' => Order::STATUS_CANCELED,
            'cancel_reason' => $reason_text,
            'custom' => CustomFieldManager::update_array($order->custom, [
                'cancellationStatus' => Order::CANCEL_STATUS_ACCEPTED,
                'cancellationTime' => now(),
                'cancellationDetails' => $request->input('details'),
            ])
        ]);

        return redirect()->route('board.orders.index')->with('toast', [
            'title' => 'Success',
            'message' => 'Order canceled successfully.',
            'type' => 'success',
        ]);
    }
}
