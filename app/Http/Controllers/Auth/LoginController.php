<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginIndex()
    {
        return view ('Auth.login');
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($credentials) && Auth::user()->role_id === 1 || Auth::user()->role_id === 2 && Auth::user()->statut === 1) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 1 || Auth::user()->role_id === 2 && Auth::user()->statut === 0) {
            return back()->with('message', "Votre compte a été desactivé");
        } elseif (Auth::attempt($credentials) && Auth::user()->role_id === 3 && Auth::user()->statut === 1) {
            return 'connecter client';
        } else {
            return back()->with([
                'message' => 'Vos identifiants sont incorrects',
            ]);
        }
    }
}
