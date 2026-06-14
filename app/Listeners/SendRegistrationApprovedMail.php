<?php

namespace App\Listeners;

use App\Events\RegistrationApproved;
use App\Mail\RegistrationApprovedMail;
use Illuminate\Support\Facades\Mail;

class SendRegistrationApprovedMail
{
    public function handle(RegistrationApproved $event): void
    {
        if (! filter_var($event->user->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        Mail::to($event->user->email)->send(new RegistrationApprovedMail($event->user));
    }
}
