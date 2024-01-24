<?php
   
namespace App\Notifications;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
  
class WelcomeMailNotification extends Notification
{
    use Queueable;
  
    public $user;
  
    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
  
    /**
     * Get the notification's delivery channels.
     *
     * @return array

     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
  
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        
        $mailData = [
            'firstname' => $this->user->firstname,
            'email' => $this->user->email,
        ];
  
            return (new MailMessage)
            ->from($this->user->email)
            ->markdown(
         'welcomeMessage.message', ['mailData' => $mailData]
                   );
    }
  
    /**
     * Get the array representation of the notification.
     *
     * @return array

     */
    public function toArray(object $notifiable): array
    {
        return [
              
        ];
    }
}