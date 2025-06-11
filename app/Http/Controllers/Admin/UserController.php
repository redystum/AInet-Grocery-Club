<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateProfileRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Models\Card;
use App\Models\User;
use App\Utils\CustomFieldManager;
use App\Utils\ToastCreator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        return view('pages.admin.user.create', compact('user'));
    }

    public function store(CreateProfileRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('photo')) {
            $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' .
                        $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('users', $filename, 'public');
            $validatedData['photo'] = $filename;
        }

        // Combine payment reference and CVV if payment type is Visa
        if (isset($validatedData['default_payment_type']) && $validatedData['default_payment_type'] === 'Visa'
            && isset($validatedData['cvv']) && isset($validatedData['default_payment_reference'])) {
            $validatedData['default_payment_reference'] = $validatedData['default_payment_reference'] . ';' . $validatedData['cvv'];
        }

        // Remove CVV from validated data as it's not a column in the users table
        if (isset($validatedData['cvv'])) {
            unset($validatedData['cvv']);
        }

        $validatedData['email_verified_at'] = now();
        $user = User::create($validatedData);

        ToastCreator::success("User $user->name created successfully.");
        return redirect()->route('board.users.index');
    }

    public function edit(User $user)
    {
        return view('pages.admin.user.edit', compact('user'));
    }

    public function update(User $user, UpdateProfileRequest $request)
    {
        $request->validated();

        $toUpdate = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'nif' => $request->input('nif'),
            'default_delivery_address' => $request->input('default_delivery_address'),
            'default_payment_type' => $request->input('default_payment_type'),
            'default_payment_reference' => $request->input('default_payment_reference'),
            'type' => $request->input('type'),
        ];

        // Combine payment reference and CVV if payment type is Visa
        if ($request->input('default_payment_type') === 'Visa' && $request->has('cvv')) {
            $toUpdate['default_payment_reference'] = $request->input('default_payment_reference') . ';' . $request->input('cvv');
        }

        if ($request->hasFile('photo')) {
            $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' .
                        $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('users', $filename, 'public');
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

        $user->update($toUpdate);

        ToastCreator::success('Profile updated successfully.');
        return redirect()->route('board.users.show', $user->id);
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

    public function resetPwd(User $user)
    {
        $status = Password::sendResetLink(
            ['email' => $user->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            ToastCreator::success('Password reset link sent successfully.');
            return redirect()->back();
        }

        ToastCreator::error('Failed to send password reset link: ' . __($status));
        return redirect()->back();
    }
}
