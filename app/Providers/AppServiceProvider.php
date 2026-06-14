<?php



namespace App\Providers;



use App\Models\Document;

use App\Models\Media;

use App\Models\User;

use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;



class AppServiceProvider extends ServiceProvider

{

    public function register()

    {

        //

    }



    public function boot()
    {
        Paginator::useBootstrap();

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers();
        });

        View::composer(['dashboard.components.sidebar', 'dashboard.components.nav'], function ($view) {

            if (auth()->check() && auth()->user()->isAdmin()) {

                $draftCount = Media::where('statut', 0)->count()

                    + Document::where('statut_publication', 0)->where('is_guest_submission', false)->count();

                $pendingRegistrationsCount = User::where('role_id', 3)->where('statut', 0)->count();

                $pendingSubmissionsCount = Document::where('is_guest_submission', true)->where('statut_publication', 0)->count();



                $view->with(compact('draftCount', 'pendingRegistrationsCount', 'pendingSubmissionsCount'));

            }

        });

    }

}

