<?php

namespace App\Console\Commands\notifications;

use App\Http\Controllers\MessagingController;
use App\Models\Appt;
use App\Models\Greeting;
use App\Models\Officehour;
use App\Models\Twilio;
use App\Models\User;
use App\Notifications\AppointmentReminder;
use App\Notifications\RemindersForDoc;
use Carbon\Carbon;
use Illuminate\Console\Command;
use stdClass;

class queueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add appointment reminders to the queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int|string
    {
        $date = Carbon::now()->hour < 18 ? Carbon::today() : Carbon::tomorrow();
        $day = $date->isToday() ? 'today' : 'tomorrow';
        $greeting = $this->getGreeting(ucfirst($day).' Reminder');
        $joinText = $this->getGreeting('reminder join text');
        $location = Greeting::where('greeting_type_id','=',12)->first();
        // get all the sessions for the day
        $appointments = Appt::whereDate('date_time', '=', $date->toDateString())
            ->booked()
            ->orderBy('date_time')
            ->get();

        foreach ($appointments as $appointment) {
            $location = in_array($appointment->type, [3,4]) ? ' at '.$location : '';
            $message = $greeting->random(). ' Reminder: '. $appointment->doctor->name . ' ' .$appointment->date_time->calendar() . ' for '. $appointment->patient->nickname.$location;
            $appointment->patient->notify(new AppointmentReminder($message));
        }
        $this->queueDocText($appointments);
        return 'Messages sent successfully';
    }

    private function queueDocText($appointments) {
        $sessionInfo = $appointments->first()->sessionHour;
        $message = [];
        $message->push($sessionInfo->doctor->name. ' hours '. $sessionInfo->date->calendar().':');
        !$sessionInfo->closed ? $message->push($sessionInfo->open->format('h:i A').' = '.$sessionInfo->close->format('h:i A')) : $message->push('CLOSED: '.($sessionInfo->reason || null));
        $message->push($appointments->count(). ' Scheduled: \n');
        $message->push($appointments->first()->date_time->format('h:i A').' - '.$appointments->last()->date_time->format('h:i A').(in_array($appointments->last()->type_id,[3,4]) ? ' NP' : ''));
        $textMessage = $message->implode(' \n ', $message);
        $users = User::where('id','<',3)->get();
        foreach($users as $user) {
            $user->notify(new RemindersForDoc($textMessage));
            $this->comment('Message to '. $user->name . ': ' . $textMessage);
        }
    }
    public function getGreeting(string $type = 'Success'): mixed
    {
        $greetings = Greeting::whereRelation('greeting_type', 'type', $type)->get()->pluck('message');
        return $greetings->random();

    }
}
