<?php

namespace App\Listeners;

use App\Events\AdminAdded;
use App\Mail\AdminCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendAdminCredentials
{
    public function handle(AdminAdded $event): void
    {
        if (filter_var($event->userEmail, FILTER_VALIDATE_EMAIL)) {
            Mail::to($event->userEmail)->send(new AdminCreatedMail($event->mailData));
        }
    }
}
