<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\NewLoginNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function show_login(): Factory|View|Application|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'You are already logged in.');
        }
        return view('pages.auth.login');
    }

    public function show_register(): Factory|View|Application|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'You are already logged in.');
        }
        return view('pages.auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->validated();

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            Mail::to(Auth::user()->email)->send(new NewLoginNotification());
            return redirect()->route('home')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email', 'remember');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $request = $request->validated();

        if (!$request['terms']) {
            return back()->withErrors([
                'terms' => 'You must accept the terms and conditions.',
            ])->onlyInput('terms');
        }

        if ($request['photo']) {
            $filename = Carbon::now()->format('dmYHis') . "_" . Str::random(10) . '.' . $request['photo']->getClientOriginalExtension();
            $request['photo']->storeAs('users', $filename, 'public');
            $request['photo'] = $filename;
        }

        $user = User::create($request);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
