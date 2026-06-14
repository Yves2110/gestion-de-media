<?php

namespace App\Providers;

use App\Events\AdminAdded;
use App\Events\RegistrationApproved;
use App\Events\RegistrationRequested;
use App\Listeners\NotifyAdminsOfRegistration;
use App\Listeners\SendAdminCredentials;
use App\Listeners\SendRegistrationApprovedMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AdminAdded::class => [SendAdminCredentials::class],
        RegistrationRequested::class => [NotifyAdminsOfRegistration::class],
        RegistrationApproved::class => [SendRegistrationApprovedMail::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
