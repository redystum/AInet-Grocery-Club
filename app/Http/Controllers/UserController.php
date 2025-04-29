<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Notifications\PasswordResetSuccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function edit()
    {
        $user = User::with('card')->find(auth()->user()->id);
        return view('pages.editProfile', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $request->validated();
        $user = User::find(auth()->user()->id);

        $toUpdate = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'nif' => $request->input('nif'),
            'default_delivery_address' => $request->input('default_delivery_address'),
            'default_payment_type' => $request->input('default_payment_type'),
            'default_payment_reference' => $request->input('default_payment_reference'),
        ];


        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Carbon::now()->format('dmYHis') . "_" . Str::random(10) . '.' . $request['photo']->getClientOriginalExtension();
            $file->storeAs('users', $filename, 'public');
            $oldPhoto = $user->photo;
            if ($oldPhoto && Storage::disk('public')->exists('users/' . $oldPhoto)) {
                Storage::disk('public')->delete('users/' . $oldPhoto);
            }
            $toUpdate['photo'] = $filename;
        }

        if ($request->has('remove_photo') && $request->input('remove_photo') == '1') {
            $oldPhoto = $user->photo;
            if ($oldPhoto && Storage::disk('public')->exists('users/' . $oldPhoto)) {
                Storage::disk('public')->delete('users/' . $oldPhoto);
            }
            $toUpdate['photo'] = null;
        }

        if ($request->has('password') && $request->input('password') !== null) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $toUpdate['password'] = Hash::make($request->input('password'));
            } else {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        $user->update($toUpdate);

        if (array_key_exists('password', $toUpdate)) {
            $user->notify(new PasswordResetSuccess());
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
