<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\CartController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\NewLogin;
use App\Notifications\Welcome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function show_login()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'You are already logged in.');
        }
        return view('pages.auth.login');
    }

    public function show_register()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'You are already logged in.');
        }
        return view('pages.auth.register');
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->email_verified_at === null) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in.',
                ])->onlyInput('email', 'remember');
            }

            if (Auth::user()->blocked) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account is blocked. Please contact support.',
                ])->onlyInput('email', 'remember');
            }

            $request->session()->regenerate();

            Auth::user()->notify(new NewLogin());

            $cartController = new CartController();
            $cartController->mergeGuestCartWithUserCart();
            return redirect()->route('home')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email', 'remember');
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        if (!$request->only('terms')) {
            return back()->withErrors([
                'terms' => 'You must accept the terms and conditions.',
            ])->onlyInput('terms');
        }

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

        $validatedData['type'] = User::TYPE_PENDING_MEMBER;
        $user = User::create($validatedData);
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Registration successful. Please check your email to activate your account.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    public function activate(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('home')->withErrors(['email' => 'Invalid activation link.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Your account is already activated.');
        }

        $user->markEmailAsVerified();

        $user->notify(new Welcome());

        return redirect()->route('login')->with('status', 'Your account has been activated successfully.');
    }
}