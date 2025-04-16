<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        $user = User::with('card', 'lastOrders')->find(auth()->user()->id);

        foreach ($user->lastOrders as $order) {
            $total_items = 0;
            foreach ($order->items as $item) {
                $total_items += $item->quantity;
            }
            $order->items_count = $total_items;
            unset($order->items);
        }
        $lastOrder = $user->lastOrders->last();
        return view('pages.profile', compact('user', 'lastOrder'));
    }
}
