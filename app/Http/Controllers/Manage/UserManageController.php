<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    public function index(){
       $admins = User::admin()->idDescending()->paginate(4);
        return view('usersManage.index',compact('admins'));
    }
    public function desactivate($id){
        $user = User::find($id);
        $user->update([
            'statut'=> 0
        ]);
        return back();
    }
    public function activate($id){
        $user = User::find($id);
        $user->update([
            'statut'=> 1
        ]);
        return back();
    }
    public function remove ($id){
        $admin = User::find($id);
        $admin->delete();
        return back()->with('success', 'Suppression éffectué avec succès');
    }
}
