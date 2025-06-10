<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Card;
use App\Models\User;
use App\Notifications\PasswordResetSuccess;
use App\Utils\CustomFieldManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function show()
    {
        $user = User::with(['card', 'lastOrders'])->find(auth()->user()->id);

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

        return view('pages.user.profile', compact('user', 'lastOrder'));
    }

    public function edit()
    {
        $user = User::find(auth()->user()->id);
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

        // Combine payment reference and CVV if payment type is Visa
        if ($request->input('default_payment_type') === 'Visa' && $request->has('cvv')) {
            $toUpdate['default_payment_reference'] = $request->input('default_payment_reference') . ';' . $request->input('cvv');
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' . $request['photo']->getClientOriginalExtension();
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

    public function updateEmployee(Request $request)
    {
        $request->validate([
            'password' => 'string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|string|min:8',
            'current_password' => 'required_with:password|string',
        ]);

        $user = User::find(auth()->user()->id);

        if ($request->has('password') && $request->input('password') !== null) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('password'));
                $user->save();
            } else {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        $user->notify(new PasswordResetSuccess());
        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
