<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureClient
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous connecter.');
        }

        if (Auth::user()->role_id !== 3) {
            return redirect()->route('dashboard')
                ->with('message', 'Accès réservé aux clients.');
        }

        if (! Auth::user()->statut) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('message', 'Votre compte a été désactivé.');
        }

        return $next($request);
    }
}
