<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2) {
            return view('dashboard.index');
        } else {
            return redirect('/');
        }
    }

   
   
}
