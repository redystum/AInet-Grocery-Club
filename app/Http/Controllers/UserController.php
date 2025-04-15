<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function show_login()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'You are already logged in.');
        }
        return view('pages.login');
    }

    public function show_register()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'You are already logged in.');
        }
        return view('pages.register');
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email', 'remember');
    }

    public function register(RegisterRequest $request)
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
