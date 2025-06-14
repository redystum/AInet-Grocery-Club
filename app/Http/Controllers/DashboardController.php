<?php

namespace App\Http\Controllers;

use App\Models\Operations;
use App\Models\Order;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $monthlySpendingData = $this->getMonthlySpendingData($user->id);

        $totalOrders = Order::where('member_id', $user->id)->count();
        $favoriteCategory = $this->getFavoriteCategory($user->id);
        $lastOrders = Order::where('member_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $recentTransactions = Operations::where('card_id', $user->card->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $userCategoryData = $this->getSpendingByCategoryData($user->id);
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

    private function getMonthlySpendingData($userId)
    {
        return Order::where('member_id', $userId)
            ->selectRaw('MONTH(date) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    private function getFavoriteCategory($userId)
    {
        return DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('items_orders.order_id', function ($query) use ($userId) {
                $query->select('id')
                    ->from('orders')
                    ->where('member_id', $userId);
            })
            ->select('categories.name', DB::raw('SUM(items_orders.quantity) as total_quantity'))
            ->groupBy('categories.name')
            ->orderByDesc('total_quantity')
            ->limit(1) // Ensure only one result is returned
            ->value('categories.name'); // Fetch the category name directly
    }

    private function getSpendingByCategoryData($userId)
    {
        return DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('items_orders.order_id', function ($query) use ($userId) {
                $query->select('id')
                    ->from('orders')
                    ->where('member_id', $userId);
            })
            ->select('categories.name', DB::raw('SUM(items_orders.subtotal) as total_spent'))
            ->groupBy('categories.name')
            ->pluck('total_spent', 'categories.name')
            ->toArray();
    }

}
