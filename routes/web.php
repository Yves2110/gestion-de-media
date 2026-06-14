<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicDocumentController;
use App\Http\Controllers\PublicMediaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\CatalogueController;
use App\Http\Controllers\Client\ClientProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Document\DocumentSubmissionController;
use App\Http\Controllers\Manage\RegistrationManageController;
use App\Http\Controllers\Manage\UserManageController;
use App\Http\Controllers\Media\AudioController;
use App\Http\Controllers\Media\VideoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Source\SourceController;
use App\Http\Controllers\Thematique\ThematiqueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('login', [LoginController::class, 'loginIndex'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('guest')->name('login.attempt');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'index'])->middleware('guest')->name('register');
Route::post('registration', [RegisterController::class, 'registration'])->middleware(['guest', 'throttle:register'])->name('register.store');

Route::get('proposer-document', [DocumentSubmissionController::class, 'create'])->name('documents.submit');
Route::post('proposer-document', [DocumentSubmissionController::class, 'store'])->middleware('throttle:public-forms')->name('documents.submit.store');

Route::get('bibliotheque/documents/{document}', [PublicDocumentController::class, 'show'])->name('public.documents.show');
Route::get('bibliotheque/documents/{document}/telecharger', [PublicDocumentController::class, 'download'])->name('public.documents.download');
Route::post('bibliotheque/documents/{document}/signaler', [PublicDocumentController::class, 'report'])->middleware('throttle:public-forms')->name('public.documents.report');

Route::get('bibliotheque/audios/{media}', [PublicMediaController::class, 'showAudio'])->name('public.audios.show');
Route::get('bibliotheque/videos/{media}', [PublicMediaController::class, 'showVideo'])->name('public.videos.show');
Route::post('bibliotheque/audios/{media}/signaler', [PublicMediaController::class, 'report'])->middleware('throttle:public-forms')->name('public.audios.report');
Route::post('bibliotheque/videos/{media}/signaler', [PublicMediaController::class, 'report'])->middleware('throttle:public-forms')->name('public.videos.report');

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'showForgetPasswordForm')->middleware('guest')->name('forget.password.get');
    Route::post('forget-password', 'submitForgetPasswordForm')->middleware(['guest', 'throttle:password-reset'])->name('forget.password.post');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->middleware('guest')->name('reset.password.get');
    Route::post('reset-password', 'submitResetPasswordForm')->middleware(['guest', 'throttle:password-reset'])->name('reset.password.post');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('changeData', 'updateData')->name('changeData');
        Route::post('changepassword', 'updatepassword')->name('changePassword');
    });
});

Route::middleware(['auth', 'ensure.admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('dashboard/onboarding/dismiss', [DashboardController::class, 'dismissOnboarding'])->name('dashboard.onboarding.dismiss');

    Route::get('admin/search', [SearchController::class, 'index'])->name('admin.search');

    Route::controller(RegisterController::class)->group(function () {
        Route::get('addAdmin', 'indexAdmin')->name('addAdmin');
        Route::post('registrationAdmin', 'registrationAdmin')->name('admin.store');
    });

    Route::controller(RegistrationManageController::class)->prefix('admin/registrations')->name('admin.registrations.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('{user}/approve', 'approve')->name('approve');
        Route::delete('{user}/reject', 'reject')->name('reject');
    });

    Route::controller(UserManageController::class)->group(function () {
        Route::get('userManage', 'index')->name('userManage');
        Route::get('userManage/{user}/edit', 'edit')->name('userManage.edit');
        Route::put('userManage/{user}', 'update')->name('userManage.update');
        Route::post('activate/{id}', 'activate')->name('activate');
        Route::post('desactivate/{id}', 'desactivate')->name('desactivate');
        Route::delete('removeManager/{id}', 'remove')->name('removeManager');
    });

    Route::resource('source', SourceController::class);
    Route::resource('thematique', ThematiqueController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('audios', AudioController::class);
    Route::resource('videos', VideoController::class);
    Route::resource('documents', DocumentController::class);

    Route::controller(AudioController::class)->prefix('audios')->name('audios.')->group(function () {
        Route::post('activate/{id}', 'activate')->name('activate');
        Route::post('desactivate/{id}', 'desactivate')->name('desactivate');
        Route::post('{id}/signaler', 'report')->name('report');
        Route::get('localisation/{id}', 'localisationIndex')->name('localisation');
        Route::post('localisation', 'addLocalisation')->name('localisation.store');
        Route::post('localisation/{id}/remove', 'removeLocalisation')->name('localisation.destroy');
    });

    Route::controller(VideoController::class)->prefix('videos')->name('videos.')->group(function () {
        Route::post('activate/{id}', 'activate')->name('activate');
        Route::post('desactivate/{id}', 'desactivate')->name('desactivate');
        Route::post('{id}/signaler', 'report')->name('report');
        Route::get('localisation/{id}', 'localisationIndex')->name('localisation');
        Route::post('localisation', 'addLocalisation')->name('localisation.store');
        Route::post('localisation/{id}/remove', 'removeLocalisation')->name('localisation.destroy');
    });

    Route::controller(DocumentController::class)->prefix('documents')->name('documents.')->group(function () {
        Route::post('activate/{id}', 'activateDocument')->name('activate');
        Route::post('desactivate/{id}', 'desactivateDocument')->name('desactivate');
        Route::post('{document}/signaler', 'report')->name('report');
        Route::get('localisation/{id}', 'localisationIndex')->name('localisation');
        Route::post('localisation', 'addLocalisation')->name('localisation.store');
        Route::post('localisation/{id}/remove', 'removeLocalisation')->name('localisation.destroy');
        Route::get('{document}/download', 'download')->name('download');
    });
});

Route::middleware(['auth', 'ensure.client'])->prefix('catalogue')->name('catalogue.')->group(function () {
    Route::get('/', [CatalogueController::class, 'index'])->name('index');
    Route::get('audios', [CatalogueController::class, 'audios'])->name('audios');
    Route::get('videos', [CatalogueController::class, 'videos'])->name('videos');
    Route::get('documents', [CatalogueController::class, 'documents'])->name('documents');
    Route::get('documents/{document}/download', [CatalogueController::class, 'downloadDocument'])->name('documents.download');
    Route::get('profil', [ClientProfileController::class, 'show'])->name('profile');
    Route::post('profil', [ClientProfileController::class, 'updateData'])->name('profile.update');
    Route::post('profil/password', [ClientProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('{type}/{id}', [CatalogueController::class, 'show'])->name('show');
});
