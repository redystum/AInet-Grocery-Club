<?php

namespace App\Http\Controllers;

use App\Models\ItemsOrder;
use App\Models\Operations;
use App\Models\Order;
use Auth;
use DateTime;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $monthlySpendingData = Order::where('member_id', $user->id)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->mapWithKeys(function ($total, $month) {
                $monthAbbr = DateTime::createFromFormat('!m', $month)->format('M');
                return [$monthAbbr => $total];
            })
            ->toArray();

        $totalOrders = Order::where('member_id', $user->id)->count();

        $favoriteCategory = ItemsOrder::join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('items_orders.order_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('orders')
                    ->where('member_id', $user->id);
            })
            ->select('categories.name', DB::raw('SUM(items_orders.quantity) as total_quantity'))
            ->groupBy('categories.name')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->value('categories.name');

        $lastOrders = Order::where('member_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $recentTransactions = Operations::where('card_id', $user->card->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $userCategoryData = ItemsOrder::join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('items_orders.order_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('orders')
                    ->where('member_id', $user->id);
            })
            ->select('categories.name', DB::raw('SUM(items_orders.subtotal) as total_spent'))
            ->groupBy('categories.name')
            ->pluck('total_spent', 'categories.name')
            ->toArray();

        $userCategoryLabels = array_keys($userCategoryData);
        $userCategoryValues = array_values($userCategoryData);

        return view('pages.user.dashboard', [
            'monthlySpendingData' => $monthlySpendingData,
            'monthlySpending' => array_sum($monthlySpendingData),
            'totalOrders' => $totalOrders,
            'favoriteCategory' => $favoriteCategory,
            'lastOrders' => $lastOrders,
            'recentTransactions' => $recentTransactions,
            'userCategoryLabels' => $userCategoryLabels,
            'userCategoryData' => $userCategoryValues,
        ]);
    }
}
