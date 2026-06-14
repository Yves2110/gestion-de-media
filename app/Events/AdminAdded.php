<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminAdded
{
    use Dispatchable, SerializesModels;

    public array $mailData;
    public string $userEmail;

    public function __construct(User $userAdmin, string $password)
    {
        $this->mailData = [
            'firstname' => $userAdmin->firstname,
            'lastname' => $userAdmin->lastname,
            'email' => $userAdmin->email,
            'password' => $password,
        ];
        $this->userEmail = $userAdmin->email;
    }
}
