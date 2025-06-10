<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use App\Utils\CustomFieldManager;
use App\Utils\ToastCreator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.admin.user.index');
    }

    public function show(User $user)
    {

        $user->load(['card', 'lastOrders']);

        foreach ($user->lastOrders as $order) {
            $total_items = 0;
            foreach ($order->items as $item) {
                $total_items += $item->quantity;
            }
            $order->setAttribute('items_count', $total_items);
            unset($order->items);
        }

        $lastOrder = $user->lastOrders->last();

        if ($user->card) {
            CustomFieldManager::self_custom_to_attribute($user->card);
        }

        if ($user->default_payment_type == Card::PAYMENT_TYPE_VISA && $user->default_payment_reference) {
            $explodedReference = explode(';', $user->default_payment_reference);
            if (count($explodedReference) == 2) {
                $user->default_payment_reference = $explodedReference[0];
                $user->setAttribute('cvv', $explodedReference[1]);
            }
        }

        CustomFieldManager::self_custom_to_attribute($user);

        return view('pages.admin.user.show', compact('user', 'lastOrder'));
    }

    public function create(User $user)
    {

    }

    public function store(Request $request)
    {

    }

    public function edit(User $user)
    {

    }

    public function update(User $user, Request $request)
    {

    }

    public function destroy(User $user)
    {
        $user->delete();

        ToastCreator::success('User deleted successfully.');
        return redirect()->route('board.users.index');
    }

    public function transactions(User $user)
    {
        $card = $user->card;

        if (!$card) {
            return redirect()->route('home')->with('error', 'No card found for this user.');
        }

        if ($card->deleted_at) {
            return redirect()->route('home')->with('error', 'This card has been deleted.');
        }

        $currentBalance = $card->balance;

        return view('pages.admin.user.transactions', compact('user', 'card', 'currentBalance'));
    }

    public function block(User $user, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $blockReason = $request->input('reason');
        $user->blocked = true;
        $user->custom = CustomFieldManager::update_or_create_array($user->custom, [
            'block_reason' => $blockReason,
            'blocked_at' => now(),
        ]);
        $user->save();

        ToastCreator::success('User blocked successfully.');
        return redirect()->back();
    }

    public function unblock(User $user)
    {
        $user->blocked = false;
        $user->save();

        ToastCreator::success('User unblocked successfully.');
        return redirect()->back();
    }
}
