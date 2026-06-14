<?php

namespace App\Http\Controllers\Manage;

use App\Events\RegistrationApproved;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationManageController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('role_id', 3)
            ->where('statut', 0)
            ->latest()
            ->paginate(15);

        return view('admin.registrations.index', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        abort_unless($user->role_id === 3 && !$user->statut, 404);

        $user->update(['statut' => 1]);

        event(new RegistrationApproved($user));

        return back()->with('success', 'Inscription validée pour ' . $user->email);
    }

    public function reject(User $user)
    {
        abort_unless($user->role_id === 3 && !$user->statut, 404);

        $user->delete();

        return back()->with('success', 'Demande d\'inscription supprimée.');
    }
}
