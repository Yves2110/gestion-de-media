<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ClientProfileController extends Controller
{
    public function show()
    {
        return view('client.profile');
    }

    public function updateData(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|min:2|max:120',
            'lastname' => 'required|string|min:2|max:120',
            'email' => 'required|email|max:190|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($request->only('firstname', 'lastname', 'email'));

        return back()->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe modifié.');
    }
}
