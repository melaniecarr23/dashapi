<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioVoiceChannel
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('twilio', $notification)) {
            return;
        }

        $message = $notification->toTwilioVoice($notifiable);

        $this->twilio->calls->create(
            $to,
            config('services.twilio.from'),
            [
                'twiml' => $this->createTwiml($greeting, $message)
            ]
        );
    }

    protected function createTwiml($greeting, $message)
    {
        return "<Response>
                    <Pause length='2'/>
                    <Say>$greeting</Say>
                    <Pause length='1'/>
                    <Say>$message</Say>
                    <Pause length='1'/>
                    <Say>Have a happy and healthy day!  Goodbye.</Say>
                    <Hangup/>
                </Response>";
    }
}
