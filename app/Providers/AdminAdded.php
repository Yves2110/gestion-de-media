<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AdminAdded extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public $mailData;
    public $userEmail;
    public function __construct(User $userAdmin, String $password)
    {
        $this->mailData['firstname'] = $userAdmin->firstname;
        $this->mailData['lastname'] = $userAdmin->lastname;
        $this->mailData['email'] = $userAdmin->email;
        $this->mailData['password'] = $password;
        $this->userEmail = $userAdmin->email;
    }
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Bootstrap services.
     *
     * @return void
     */
     /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
