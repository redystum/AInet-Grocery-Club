<?php

namespace App\Http\Controllers;

use App\Models\Settings;
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
            return redirect()->route('user.card.index')->with('error', 'You already have a card.');
        }

        //TODO...

        return redirect()->route('user.card.index')->with('success', 'Card created successfully.');

    }
}
