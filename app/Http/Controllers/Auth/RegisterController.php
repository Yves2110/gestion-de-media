<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegistrationRequested;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Events\AdminAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'uuid' => (string) Str::uuid(),
            'statut' => 0,
        ]);

        event(new RegistrationRequested($user));

        return redirect()->route('login')->with('success', 'Inscription enregistrée. Un administrateur validera votre compte avant connexion.');
    }

    public function indexAdmin()
    {
        Gate::authorize('manage-admins');

        return view('Auth.registerAdmin');
    }

    public function registrationAdmin(RegisterAdminRequest $request)
    {
        Gate::authorize('manage-admins');

        $password = Str::random(12);

        $userAdmin = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => 2,
            'uuid' => (string) Str::uuid(),
            'statut' => 1,
        ]);

        event(new AdminAdded($userAdmin, $password));

        return back()->with('message', 'Administrateur ajouté avec succès');
    }
}
