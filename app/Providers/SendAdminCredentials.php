<?php

namespace App\Providers;

use App\Mail\AdminCreatedMail;
use App\Mail\OrganizationCreatedMail;
use App\Providers\OrganizationAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAdminCredentials
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\OrganizationAdded  $event
     * @return void
     */
    public function handle(AdminAdded $event)
    {
        $userEmail = $event->userEmail;
        $mailData = $event->mailData;
    
        // Vérifiez que $userEmail est une adresse e-mail valide
        if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            Mail::to($userEmail)->send(new AdminCreatedMail($mailData));
        } 
    }
    
}
