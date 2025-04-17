<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFilterRequest;
use App\Models\Order;
use ZipArchive;

class OrderController extends Controller
{
    public function index(OrderFilterRequest $request)
    {
        $request->validated();

        $orders = $this->getOrderWithFilters();

        foreach ($orders as $order) {
            $total_items = 0;
            $total_discount = 0; // in cents

            foreach ($order->items as $item) {
                $total_items += $item->quantity;
                $total_discount += $item->discount * $item->quantity;
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

    public function export(OrderFilterRequest $request)
    {
        $request->validated();

        $orders = $this->getOrderWithFilters();

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

    private function getOrderWithFilters()
    {
        $query = auth()->user()->orders()->with(['products']);

        if (request('date_range')) {
            $days = (int)request('date_range');
            $query->where('created_at', '>=', now()->subDays($days));
        }

        switch (request('sort', 'newest')) {
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

        $perPage = request('per_page', 5);
        return $query->paginate($perPage)
            ->appends(request()->query());
    }
}
