<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserManageController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-admins');

        $admins = User::admin()->idDescending()->paginate(10);

        return view('usersManage.index', compact('admins'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('usersManage.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'firstname' => 'required|string|min:2',
            'lastname' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|in:1,2',
        ]);

        if (!Auth::user()->isSuperAdmin()) {
            $request->merge(['role_id' => $user->role_id]);
        }

        $user->update($request->only('firstname', 'lastname', 'email', 'role_id'));

        return redirect()->route('userManage')->with('success', 'Utilisateur mis à jour');
    }

    public function desactivate($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('toggleStatus', $user);

        $user->update(['statut' => 0]);

        return back();
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('toggleStatus', $user);

        $user->update(['statut' => 1]);

        return back();
    }

    public function remove($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();

        return back()->with('success', 'Suppression effectuée avec succès');
    }
}
