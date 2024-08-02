<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentsNeedRescheduled extends Notification
{
    use Queueable;

    private $patients;
    private $date;

    /**
     * Create a new notification instance.
     * @param $patients
     * @param $date
     */
    public function __construct($patients, $date)
    {
        //
        $this->patients = $patients;
        $this->date = $date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['twilio'];
    }
    public function toTwilio($notifiable)
    {

        $message = "Date: {$notifiable->date} appointments need rescheduled: {$this->notifiable->patients}";

        return (new TwilioMessage())
            ->content($message);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
