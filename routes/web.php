<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Manage\UserManageController;
use App\Http\Controllers\Media\AudioController;
use App\Http\Controllers\Media\VideoController;
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
    Route::resource('source', SourceController::class);
    Route::resource('thematique', ThematiqueController::class);
    Route::resource('audios', AudioController::class);
    Route::resource('videos', VideoController::class);
    Route::resource('documents',DocumentController::class);
  
    Route::controller(AudioController::class)->group(function(){
        Route::get('activateAudio/{id}','activate')->name('activateAudio');
        Route::get('desactivateAudio/{id}', 'desactivate')->name('desactivateAudio');
        Route::get('audioLocalisation/{id}', 'localisationIndex')->name('audioLocalisation');
        Route::post('localisationAdded','addLocalisation')->name('audioLocalisationAdd');
        Route::get('removeLocalisation/{id}', 'removeLocalisation')->name('destroyLocalisation');
    });
    
    Route::controller(VideoController::class)->group(function(){
        Route::get('activateVideo/{id}','activate')->name('activateVideo');
        Route::get('desactivateVideo/{id}', 'desactivate')->name('desactivateVideo');
        Route::get('videoLocalisation/{id}', 'localisationIndex')->name('videoLocalisation');
        Route::post('localisationAdded','addLocalisation')->name('videoLocalisationAdd');
        Route::get('removeLocalisation/{id}', 'removeLocalisation')->name('destroyLocalisation');
    });

    // a revoir
    Route::controller(DocumentController::class)->group(function(){
        Route::get('activateDocument/{id}','activateDocument')->name('activateDocument');
        Route::get('desactivateDocument/{id}', 'desactivateDocument')->name('desactivateDocument');
        Route::get('documentLocalisation/{id}', 'localisationIndex')->name('documentLocalisation');
        Route::post('localisationAdded','addLocalisation')->name('documentLocalisationAdd');
        Route::get('removeLocalisation/{id}', 'removeLocalisation')->name('destroyLocalisation');
    });
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('changeData', 'updateData')->name('changeData');
        Route::post('changepassword', 'updatepassword')->name('changePassword');
    });

    Route::controller(UserManageController::class)->group(function () {
        Route::get('userManage', 'index')->name('userManage');
        Route::get('activate/{id}', 'activate')->name('activate');
        Route::get('desactivate/{id}', 'desactivate')->name('desactivate');
        Route::get('removeManager/{id}', 'remove')->name('removeManager');
    });


Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'showForgetPasswordForm')->name('forget.password.get');
    Route::post('forget-password', 'submitForgetPasswordForm')->name('forget.password.post');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('reset.password.get');
    Route::post('reset-password', 'submitResetPasswordForm')->name('reset.password.post');
});

Route::get('/', [LoginController::class, 'loginIndex']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('register', [RegisterController::class, 'index']);
Route::post('logout', [LoginController::class,'logout'])->name('logout');
