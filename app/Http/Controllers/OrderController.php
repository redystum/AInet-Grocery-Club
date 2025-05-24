<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFilterRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use ZipArchive;
use App\Utils\CustomFieldManager;

class OrderController extends Controller
{
    public function index(OrderFilterRequest $request)
    {
        $request->validated();

        $orders = $this->getOrderWithFilters($request);

        foreach ($orders as $order) {
            $total_items = 0;
            $total_discount = 0; // in cents

            foreach ($order->items as $item) {
                $total_items += $item->quantity;
                $total_discount += $item->discount * $item->quantity;
            }

            $order->setAttribute("items_count", $total_items);
            $order->setAttribute("total_discount", $total_discount);

            CustomFieldManager::self_custom_to_attribute($order);
        }

        return view('pages.user.orders', compact('orders'));
    }

    public function receipt(Order $order)
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

    public function export(OrderFilterRequest $request)
    {
        $request->validated();

        $orders = $this->getOrderWithFilters($request);

        // create a zip file with all the receipts
        $zip = new ZipArchive();
        $zip_name = 'receipts_' . now()->format('Y-m-d_H-i-s') . '.zip';
        $zip_path = storage_path('app/private/receipts/' . $zip_name);
        if ($zip->open($zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($orders as $order) {
                $path = storage_path('app/private/receipts/' . $order->pdf_receipt);
                if (file_exists($path)) {
                    $zip->addFile($path, $order->pdf_receipt);
                }
            }
            $zip->close();
        }

        // download the zip file and delete it after download
        return response()->download($zip_path)->deleteFileAfterSend(true);
    }

    public function cancel(Order $order)
    {
        if ($order->member_id != auth()->user()->id) {
            abort(404);
        }

        if ($order->status != Order::STATUS_PENDING) {
            return redirect()->route('orders')->with('toast', [
                'title' => 'Error',
                'message' => 'You cannot cancel this order',
                'type' => 'error',
            ]);
        }

        CustomFieldManager::self_custom_to_attribute($order);

        if ($order->cancellationStatus != null) {
            abort(403);
        }

        $total_items = 0;
        $total_discount = 0; // in cents

        foreach ($order->items as $item) {
            $total_items += $item->quantity;
            $total_discount += $item->discount * $item->quantity;
        }

        $order->setAttribute("items_count", $total_items);
        $order->setAttribute("total_discount", $total_discount);

        return view('pages.user.cancel_order', compact('order'));
    }

    public function cancelConfirm(Order $order, Request $request)
    {
        if ($order->member_id != auth()->user()->id) {
            abort(404);
        }

        if ($order->status != Order::STATUS_PENDING) {
            return redirect()->route('orders')->with('toast', [
                'title' => 'Error',
                'message' => 'You cannot cancel this order',
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
                $reason_text = 'Found cheaper elsewhere';
                break;
            case 2:
                $reason_text = 'Changed my mind';
                break;
            case 3:
                $reason_text = 'Shipping takes too long';
                break;
            case 4:
                $reason_text = 'Ordered by mistake';
                break;
        }

        $order->update([
            'cancel_reason' => $reason_text,
            'custom' => CustomFieldManager::update_array($order->custom, [
                'cancellationStatus' => Order::CANCEL_STATUS_PENDING,
                'cancellationTime' => now(),
                'cancellationDetails' => $request->input('details'),
            ])
        ]);

        return redirect()->route('orders')->with('toast', [
            'title' => 'Success',
            'message' => 'Cancellation request sent successfully',
            'type' => 'success',
        ]);
    }


    private function getOrderWithFilters(OrderFilterRequest $request)
    {
        $query = auth()->user()->orders()->with(['products']);

        if ($request->input('date_range')) {
            $days = (int)$request->input('date_range');
            $query->where('created_at', '>=', now()->subDays($days));
        }

        switch ($request->input('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'price_asc':
                $query->orderBy('total');
                break;
            case 'price_desc':
                $query->orderByDesc('total');
                break;
            case 'status':
                $query->orderBy('status');
                break;
            default: // newest
                $query->orderByDesc('created_at');
        }

        $perPage = $request->input('per_page', 5);
        return $query->paginate($perPage)
            ->appends($request->only(['date_range', 'sort', 'per_page']));
    }
}
