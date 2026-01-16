<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\ArkeselSmsChannel; // Import custom channel

class WelcomeTenant extends Notification
{
    use Queueable;

    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     * Use your custom channel string here.
     */
    public function via(object $notifiable): array
    {
        return [ArkeselSmsChannel::class]; // Use the class reference
    }

    /**
     * Get the Arkesel representation of the notification.
     * This method is called by the ArkeselSmsChannel.
     */
    public function toArkesel(object $notifiable): string
    {
        // $notifiable is the model receiving the notification (e.g., the User/Tenant)
        return
        #Hello {$notifiable->name},
        "{$this->message}";
    }
}
