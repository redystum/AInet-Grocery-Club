<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Settings;
use App\Services\Payment;
use App\Utils\CustomFieldManager;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $card = $user->card;

        if (!$card) {
            return redirect()->route('home')->with('error', 'No card found for this user.');
        }

        return view('pages.user.card.index', compact('card'));
    }

    public function create()
    {
        if (auth()->user()->card) {
            return redirect()->route('user.card.index')->with('error', 'You already have a card.');
        }
        $user = auth()->user();

        $fee = Settings::get()->membership_fee ?? 0.00;

        $canCreateCard = $user->default_payment_type && $user->default_payment_reference;

        if ($user->default_payment_type == Card::PAYMENT_TYPE_VISA && $user->default_payment_reference) {
            $explodedReference = explode(';', $user->default_payment_reference);
            if (count($explodedReference) == 2) {
                $user->default_payment_reference = $explodedReference[0];
                $user->setAttribute('cvv', $explodedReference[1]);
            }
        }

        if (Payment::pay(
                $user->default_payment_type,
                $user->default_payment_reference,
                $user->cvv,
            ) === false) {
            return view('pages.user.card.create', compact('user', 'fee', 'canCreateCard'))->withErrors(['reference' => 'Invalid payment details. Please check your payment method and try again.']);
        }


        return view('pages.user.card.create', compact('user', 'fee', 'canCreateCard'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'nickname' => 'nullable|string|max:255',
        ]);

        $fee = Settings::get()->membership_fee ?? 0.00;

        if ($request->input('amount') < $fee) {
            return redirect()->back()->withErrors([
                'amount' => 'The amount must be at least the membership fee of ' . number_format($fee, 2) . '€.',
            ]);
        }

        $user = auth()->user();

        if ($user->card) {
            return redirect()->route('profile')->with('error', 'You already have a card.');
        }

        if ($user->default_payment_type == Card::PAYMENT_TYPE_VISA && $user->default_payment_reference) {
            $explodedReference = explode(';', $user->default_payment_reference);
            if (count($explodedReference) == 2) {
                $user->default_payment_reference = $explodedReference[0];
                $user->setAttribute('cvv', $explodedReference[1]);
            }
        }

        if (Payment::pay(
            $user->default_payment_type,
            $user->default_payment_reference,
            $user->cvv,
        ) === false) {
            return redirect()->back()->withErrors(['reference' => 'Invalid payment details. Please check your payment method and try again.']);
        }

        $balance = $request->input('amount') - $fee;

        $card = $user->card()->create([
            'balance' => $balance,
            'card_number' => Card::generate_card_number($user->id),
            'custom' => CustomFieldManager::update_or_create_array(null, [
                'nickname' => $request->input('nickname'),
            ], true)
        ]);

        if (!$card) {
            return redirect()->back()->withErrors(['error' => 'Failed to create card. Please try again later.']);
        }

        return redirect()->route('profile')->with('success', 'Card created successfully.');

    }

    public function charge()
    {
        $user = auth()->user();
        $card = $user->card;

        if (!$card) {
            return redirect()->route('home')->with('error', 'No card found for this user.');
        }

        if ($card->deleted_at) {
            return redirect()->route('home')->with('error', 'This card has been deleted.');
        }

        $total_items = 0;
        $lastOrder = $user->lastOrders->last();
        if ($lastOrder) {
            foreach ($lastOrder->items as $item) {
                $total_items += $item->quantity;
            }
            $lastOrder->setAttribute('items_count', $total_items);
        }
        unset($lastOrder->items);

        $canChargeCard = $user->default_payment_type && $user->default_payment_reference;

        if ($user->default_payment_type == Card::PAYMENT_TYPE_VISA && $user->default_payment_reference) {
            $explodedReference = explode(';', $user->default_payment_reference);
            if (count($explodedReference) == 2) {
                $user->default_payment_reference = $explodedReference[0];
                $user->setAttribute('cvv', $explodedReference[1]);
            }
        }

        if (Payment::pay(
                $user->default_payment_type,
                $user->default_payment_reference,
                $user->cvv,
            ) === false) {
            return view('pages.user.card.charge', compact('card', 'user', 'canChargeCard', 'lastOrder'))->withErrors(['reference' => 'Invalid payment details. Please check your payment method and try again.']);
        }

        return view('pages.user.card.charge', compact('card', 'user', 'canChargeCard', 'lastOrder'));

    }

    public function update(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $card = $user->card;
        if (!$card) {
            return redirect()->route('home')->with('error', 'No card found for this user.');
        }

        if ($card->deleted_at) {
            return redirect()->route('home')->with('error', 'This card has been deleted.');
        }

        if (Payment::pay(
                $user->default_payment_type,
                $user->default_payment_reference,
                $user->cvv,
            ) === false) {
            return redirect()->back()->withErrors(['reference' => 'Invalid payment details. Please check your payment method and try again.']);
        }

        $card->balance += $request->input('amount');
        $card->save();

        return redirect()->route('profile')->with('success', 'Card balance updated successfully.');
    }
}
