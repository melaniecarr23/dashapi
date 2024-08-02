<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\TwilioMessage;
use Illuminate\Notifications\Notification;

class AppointmentAffected extends Notification
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new notification instance.
     *
     * @param $appointment
     * @param $nicknames
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->cell) {
            $channels[] = 'twilio';
        } elseif ($notifiable->home) {
            $channels[] = 'phone';
        } elseif ($notifiable->email) {
            $channels[] = 'mail';
        }

        return $channels;

    }

    /**
     * Get the Twilio representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\TwilioMessage
     */
    public function toTwilio($notifiable)
    {

        $message = "{$notifiable->appointment->patient->nickname}'s appointment at {$this->appointment->date_time->format('m/d/y h:i A')} needs to be rescheduled. The office will be closed at that time. Reply or schedule online at https://innerhealer.org/calendar - Dr. Carr";

        return (new TwilioMessage())
            ->content($message);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Reschedule')
            ->line("Appointment: {$notifiable->nickname} at {$this->appointment->date_time->format('m/d/y h:i A')}")
            ->line('Please contact us to reschedule your appointment.')
            ->line("You can also choose a new appointment online at https://innerhealer.org/calendar")
            ->line("Have a happy and healthy day!  - Dr. Carr");
    }

    /**
     * Handle phone call notification.
     *
     * @param mixed $notifiable
     * @return void
     */
    public function toPhone($notifiable)
    {
        // Logic to handle phone call notification (e.g., using Twilio Voice API)
        $message = "We need to reschedule your appointment on {$this->appointment->date_time->format('m/d/y h:i A')} to another time. Please call or text doctor Carr to reschedule, or pick a new appointment online at inner healer dot org.  Thank you!";

        // For now, log the phone call notification
        \Log::info('Phone call notification: ' . $message);
    }
}
