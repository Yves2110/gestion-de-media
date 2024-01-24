<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateData(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
        ]);
        Auth::user()->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'updated_at' => Auth::user()->updated_at
        ]);
        return back()->with('success', 'Information modifiée');
    }

    public function updatePassword(Request $request)
    {
        $password = $request->validate([
            'password'=> 'required|min:6|',
            'confirm_password' => 'required|same:password',
        ]);
        Auth::user()->update([
            'password' => Hash::make($request->password),
            'updated_at' => Auth::user()->updated_at
        ]);
        return back()->with('success', 'Mot de passe modifiée');
    }
}
