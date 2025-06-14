<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->blocked) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is blocked. Please contact support.',
            ]);
        }
        return $next($request);
    }
}
