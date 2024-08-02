<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'twilio'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('This is a reminder for your appointment.')
            ->line('Appointment Date: ' . $this->appointment->date_time->format('m/d/Y h:i A'))
            ->line('Thank you for using our application!');
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioMessage)
            ->content('Reminder: Your appointment is scheduled for ' . $this->appointment->date_time->format('m/d/Y h:i A'));
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
        ];
    }
}
