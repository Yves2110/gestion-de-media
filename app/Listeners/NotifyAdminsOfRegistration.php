<?php

namespace App\Listeners;

use App\Events\RegistrationRequested;
use App\Mail\NewRegistrationAlertMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyAdminsOfRegistration
{
    public function handle(RegistrationRequested $event): void
    {
        $admins = User::admin()->where('statut', 1)->get();

        foreach ($admins as $admin) {
            if (! filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            Mail::to($admin->email)->send(new NewRegistrationAlertMail($event->user));
        }
    }
}
