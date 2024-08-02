<?php

namespace App\Console\Commands\calendar;

use App\Models\Day;
use App\Models\Officehour;
use App\Models\SessionHour;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class OpenAvailableSlots extends Command
{
    protected $signature = 'open:slots';
    protected $description = 'Add days to ';

    public function handle()
    {
        $now = Carbon::now();
        $lastOfficeHourClose = $this->getLastOfficeHourClose();

        // Determine the start of the week based on the current time
        $weekStart = $now->gte($lastOfficeHourClose) ? $now->startOfWeek()->addWeek() : $now->firstWeekDay();

        $this->info('Start date determined: ' . $weekStart->toDateString());

        // Populate the days table
        $this->populateDays($weekStart);

        // Assign session_hour_id to existing appointments
        $this->assignSessionHourIdToAppointments();
    }

    private function getLastOfficeHourClose()
    {
        $lastOfficeHour = Officehour::where('publish', '=', 1)
            ->orderBy('dayslot', 'desc')
            ->with(['sessionHours' => function ($query) {
                $query->orderBy('close', 'desc');
            }])
            ->first();

        return Carbon::parse($lastOfficeHour->sessionHours->first()->close);
    }

    private function populateDays($weekStart)
    {
        $lastDate = $weekStart->copy()->addWeeks(6)->lastWeekday();
        $existingDates = Day::pluck('day')->toArray();

        // Get weekdays that aren't already there
        $datesToAdd = CarbonPeriod::create($weekStart, $lastDate)->filter(function ($date) use ($existingDates) {
            return $date->isWeekday() && !in_array($date->toDateString(), $existingDates);
        });

        foreach ($datesToAdd as $date) {
            Day::firstOrCreate(['day' => $date->toDateString()]);
        }

        $this->info('Days table populated successfully.');
    }

    private function assignSessionHourIdToAppointments()
    {
        $today = Carbon::today();
        $appointments = Appt::whereDate('date_time', '>', $today)->whereNull('session_hour_id')->get();

        foreach ($appointments as $appointment) {
            $sessionHour = SessionHour::where('date', $appointment->date_time->toDateString())
                ->where('open', '<=', $appointment->date_time)
                ->where('close', '>=', $appointment->end_time)
                ->first();

            if ($sessionHour) {
                $appointment->session_hour_id = $sessionHour->id;
                $appointment->save();
            }
        }

        $this->info('Appointments updated with the correct session_hour IDs successfully.');
    }
}
