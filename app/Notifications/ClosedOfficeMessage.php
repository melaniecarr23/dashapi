<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ClosedOfficeNotification extends Notification
{
    public function via($notifiable)
    {
        return [TwilioVoiceChannel::class];
    }

    public function toTwilioVoice($notifiable)
    {
        return "Hello, our office is currently closed. Please call back during our business hours. Thank you!";
    }

    public function routeNotificationForTwilio($notifiable)
    {
        // Return the phone number where you want to send the voice message
        return $notifiable->phone;
    }
}
