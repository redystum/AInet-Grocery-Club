<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ItemsOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'year' => 'nullable|integer|min:2000|max:' . now()->year,
        ]);

        $stats = [
            'totalMembers' => User::where('type', 'member')->count(),
            'monthlyRevenue' => Order::where('status', Order::STATUS_COMPLETED)
                ->whereMonth('date', now()->month)
                ->sum('total'),
            'pendingOrders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'lowStockItems' => Product::whereColumn('stock', '<=', 'stock_lower_limit')->count(),
        ];

        $year = $request->input('year', now()->year);

        $monthlyRevenueData = Order::selectRaw('MONTH(date) as month, SUM(total) as total')
            ->where('status', Order::STATUS_COMPLETED)
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month - 1 => $item->total];
            })
            ->toArray();


        $categoryData = Category::with(['products.items' => function ($query) use ($year) {
            $query->whereHas('order', function ($q) use ($year) {
                $q->where('status', Order::STATUS_COMPLETED)
                    ->whereYear('date', $year);
            });
        }])->get()
            ->map(function ($category) {
                $total = $category->products->flatMap->items->sum('subtotal');
                return (object)[
                    'name' => $category->name,
                    'total' => $total,
                ];
            })
            ->filter(function ($item) {
                return $item->total > 0;
            })
            ->values();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'stock_lower_limit')
            ->orderBy('stock')
            ->take(10)
            ->get();

        $existentYears = Order::select(DB::raw('YEAR(date) as year'))
            ->where('status', Order::STATUS_COMPLETED)
            ->groupBy('year')
            ->pluck('year');

        return view('pages.admin.dash', array_merge($stats, [
            'monthlyRevenueData' => $monthlyRevenueData,
            'categoryLabels' => $categoryData->pluck('name'),
            'categoryData' => $categoryData->pluck('total'),
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'existentYears' => $existentYears,
        ]));
    }

    public function exportOrders()
    {
        $orders = Order::with(['user', 'items.product'])
            ->where('status', Order::STATUS_COMPLETED)
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($orders) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Order ID', 'Date', 'Member', 'Total Items',
                'Subtotal', 'Shipping', 'Total', 'Status'
            ]);

            // Data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->date->format('Y-m-d'),
                    $order->user->name,
                    $order->total_items,
                    $order->total - $order->shipping_cost,
                    $order->shipping_cost,
                    $order->total,
                    $order->status,
                ]);
            }

            fclose($file);
            flush();
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportMembers()
    {
        $members = User::where('type', 'member')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($members) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Gender', 'NIF',
                'Member Since', 'Total Orders', 'Total Spent'
            ]);

            // Data rows
            foreach ($members as $member) {
                fputcsv($file, [
                    $member->id,
                    $member->name,
                    $member->email,
                    $member->gender,
                    $member->nif,
                    $member->created_at->format('Y-m-d'),
                    $member->orders()->count(),
                    $member->orders()->sum('total'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportProducts()
    {
        $products = Product::with('category')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Name', 'Category', 'Price', 'Stock',
                'Lower Limit', 'Upper Limit', 'Total Sold'
            ]);

            // Data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name,
                    $product->price,
                    $product->stock,
                    $product->stock_lower_limit,
                    $product->stock_upper_limit,
                    $product->items()->sum('quantity'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}