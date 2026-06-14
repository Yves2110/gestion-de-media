<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous connecter.');
        }

        if (!in_array(Auth::user()->role_id, [1, 2], true)) {
            return redirect()->route('catalogue.index')
                ->with('message', 'Accès non autorisé.');
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
