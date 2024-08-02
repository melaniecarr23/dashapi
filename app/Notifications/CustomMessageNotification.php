<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class CustomMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        $channels = ['database'];

        if ($notifiable->cell) {
            $channels[] = 'nexmo';
        }

        if ($notifiable->email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line($this->message)
            ->action('View Details', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content($this->message);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
