<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    public function loginIndex()
    {
        return view('Auth.login');
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->with([
                'message' => 'Vos identifiants sont incorrects',
            ]);
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 1 && Auth::user()->statut === 1) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 2  && Auth::user()->statut === 1) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 2  && Auth::user()->statut === 0) {
            return back()->with('message', "Votre compte a été desactivé");
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 3 && Auth::user()->statut === 1) {
            return 'connecter client';
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 3 && Auth::user()->statut === 0) {
            return back()->with('message', "Votre compte a été desactivé");
        } else {
            return back()->with([
                'message' => 'Vos identifiants sont incorrects',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    
}
