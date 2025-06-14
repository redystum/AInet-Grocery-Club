<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operations;
use App\Models\Order;
use App\Notifications\CancelledOrder;
use App\Notifications\OrderCompleted;
use App\Notifications\RefusedCancellationOrder;
use App\Utils\CustomFieldManager;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $order->custom = CustomFieldManager::update_or_create_array($order->custom, [
            'deliveryTime' => now(),
        ]);
        $order->save();

        foreach ($order->items as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        // Calculate total discount for the receipt
        $total_discount = 0;
        foreach ($order->items as $item) {
            $total_discount += $item->discount * $item->quantity;
        }

        // Generate PDF receipt
        $order->load(['user', 'products']);
        $pdf = PDF::loadView('pdfs.receipt', [
            'order' => $order,
            'total_discount' => $total_discount
        ]);

        $pdfFileName = 'order_receipt_' . $order->id . '.pdf';
        $pdfPath = 'receipts/' . $pdfFileName;
        Storage::disk('local')->put($pdfPath, $pdf->output());
        // Save PDF path in order
        $order->pdf_receipt = $pdfFileName;
        $order->save();

        // Send email with order details and receipt
        $order->user->notify(new OrderCompleted(
            $order->id,
            now()->format('d-m-Y H:i'),
            $order->delivery_address,
            $order->items->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->product->getImage(),
                ];
            })->toArray(),
            $pdfPath,
            $pdfFileName
        ));

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
            return redirect()->route('board.orders.index')->with('toast', [
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

        DB::transaction(function () use ($order, $reason_text, $request) {
            $order->update([
                'status' => Order::STATUS_CANCELED,
                'cancel_reason' => $reason_text,
                'custom' => CustomFieldManager::update_or_create_array($order->custom, [
                    'cancellationStatus' => Order::CANCEL_STATUS_ACCEPTED,
                    'cancellationTime' => now(),
                    'cancellationDetails' => $request->input('details'),
                ])
            ]);

            $user = $order->user;
            $user->card->increment('balance', $order->total);
            Operations::create([
                'card_id' => $user->id,
                'type' => Operations::TYPE_CREDIT,
                'value' => $order->total,
                'date' => now()->format('Y-m-d'),
                'debit_type' => null,
                'credit_type' => Operations::TYPE_CREDIT_ORDER_CANCEL,
                'payment_type' => null,
                'payment_reference' => null,
                'order_id' => $order->id
            ]);

        });

        $order->user->notify(new CancelledOrder(
            $order->id,
            $order->created_at->format('d-m-Y H:i:s'),
            $reason_text,
            $request->input('details'),
            $order->total
        ));

        return redirect()->route('board.orders.index')->with('toast', [
            'title' => 'Success',
            'message' => 'Order canceled successfully.',
            'type' => 'success',
        ]);
    }

    public function cancelConfirm(Order $order)
    {
        if ($order->status != Order::STATUS_PENDING) {
            return redirect()->route('board.orders.index')->with('toast', [
                'title' => 'Error',
                'message' => 'You cannot cancel this order',
                'type' => 'error',
            ]);
        }

        DB::transaction(function () use ($order) {

            $order->update([
                'status' => Order::STATUS_CANCELED,
                'custom' => CustomFieldManager::update_or_create_array($order->custom, [
                    'cancellationStatus' => Order::CANCEL_STATUS_ACCEPTED,
                    'cancellationTime' => now(),
                ])
            ]);

            $user = $order->user;
            $user->card->increment('balance', $order->total);
            Operations::create([
                'card_id' => $user->id,
                'type' => Operations::TYPE_CREDIT,
                'value' => $order->total,
                'date' => now()->format('Y-m-d'),
                'debit_type' => null,
                'credit_type' => Operations::TYPE_CREDIT_ORDER_CANCEL,
                'payment_type' => null,
                'payment_reference' => null,
                'order_id' => $order->id
            ]);
        });

        $order->user->notify(new CancelledOrder(
            $order->id,
            $order->created_at->format('d-m-Y H:i:s'),
            $order->cancel_reason ?? 'No reason provided',
            CustomFieldManager::get_field($order, 'cancellationDetails') ?? 'No details provided',
            $order->total
        ));

        return redirect()->route('board.orders.index')->with('toast', [
            'title' => 'Success',
            'message' => 'Order canceled successfully.',
            'type' => 'success',
        ]);
    }

    public function cancelReject(Order $order)
    {
        if ($order->status != Order::STATUS_PENDING) {
            return redirect()->route('board.orders.index')->with('toast', [
                'title' => 'Error',
                'message' => 'You cannot reject this order',
                'type' => 'error',
            ]);
        }

        $order->update([
            'custom' => CustomFieldManager::update_or_create_array($order->custom, [
                'cancellationStatus' => Order::CANCEL_STATUS_REFUSED,
                'cancellationTime' => now(),
            ])
        ]);

        $order->user->notify(new RefusedCancellationOrder(
            $order->id,
            $order->cancel_reason ?? 'No reason provided',
            CustomFieldManager::get_field($order, 'cancellationDetails') ?? 'No details provided',
            CustomFieldManager::get_field($order, 'expectedShipDate') ?? "No expected ship date provided",
        ));

        return redirect()->route('board.orders.index')->with('toast', [
            'title' => 'Success',
            'message' => 'Cancellation request rejected successfully.',
            'type' => 'success',
        ]);
    }

    public function receipt(Order $order)
    {
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
