<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Manage\UserManageController;
use App\Http\Controllers\Media\AudioController;
use App\Http\Controllers\Source\SourceController;
use App\Http\Controllers\Thematique\ThematiqueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['admin'])->group(function () {
    Route::controller(RegisterController::class)->group(function () {
        Route::post('registration',  'registration')->name('register.store');
        Route::get('addAdmin', 'indexAdmin')->name('addAdmin');
        Route::post('registrationAdmin', 'registrationAdmin')->name('admin.store');
    });
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('logout', 'logout')->name('logout');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::post('changeData', 'updateData')->name('changeData');
        Route::post('changepassword', 'updatepassword')->name('changePassword');
    });
    
    Route::controller(UserManageController::class)->group(function () {
        Route::get('userManage', 'index')->name('userManage');
        Route::get('activate/{id}', 'activate')->name('activate');
        Route::get('desactivate/{id}', 'desactivate')->name('desactivate');
        Route::get('removeManager/{id}', 'remove')->name('removeManager');
    });
});

Route::controller(UserManageController::class)->group(function () {
    Route::get('forget-password', 'showForgetPasswordForm')->name('forget.password.get');
    Route::post('forget-password', 'submitForgetPasswordForm')->name('forget.password.post');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('reset.password.get');
    Route::post('reset-password', 'submitResetPasswordForm')->name('reset.password.post');
});

Route::resource('source', SourceController::class);
Route::resource('thematique', ThematiqueController::class);
Route::resource('audio', AudioController::class);
Route::get('/', [LoginController::class, 'loginIndex']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('register', [RegisterController::class, 'index']);
