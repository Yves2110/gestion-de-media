<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRegistrationAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function build()
    {
        return $this->subject('Nouvelle demande d\'inscription')
            ->view('mails.new-registration-alert', [
                'user' => $this->user,
                'reviewUrl' => route('admin.registrations.index'),
            ]);
    }
}
