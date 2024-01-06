<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Providers\AdminAdded;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('Auth.register');
    }
    public function registration(RegisterRequest $request)
    {
   
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'uuid' => Str::uuid(),
            'statut'=> 1
        ]);

        $user->notify(new \App\Notifications\WelcomeMailNotification($user));

        return back()->with('success', 'Votre inscription à été effectué avec succcès!!! Connectez vous');
    }
    public function indexAdmin()
    {
        return view('Auth.registerAdmin');
    }
    public function registrationAdmin(RegisterAdminRequest $request)
    {
        $request->validate([
            'firstname' => 'bail|required|string|min:2',
            'lastname' => 'bail|required|string|min:2',
            'email' => 'bail|required|email|unique:users',
        ]);

        $password = substr(str_shuffle(Hash::make(Str::random(10))), 0, 15);

        $userAdmin = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' =>   $input['password'] = Hash::make($password),
            'role_id' => 2,
            'uuid' => Str::random(30),
            'statut'=> 1
        ]);
        event(new AdminAdded($userAdmin, $password));
        return back()->with('message','Ajout de l\'admnistrateur avec succès');
    }
}
