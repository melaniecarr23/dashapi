<?php

namespace Database\Seeders;

use App\Models\Appt;
use App\Models\ModifiedHour;
use App\Models\Officehour;
use App\Models\SessionHour;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

class WeekdaysTableSeeder extends Seeder
{
    public function run()
    {
        // Determine the start date
        if (Carbon::today()->isFriday() || Carbon::today()->isWeekend()) {
            $startDate = Carbon::today()->next('Monday');
        } else {
            $startDate = Carbon::today()->startOfWeek(Carbon::MONDAY);
        }

        // Determine the end date (six weeks later, last weekday)
        $endDate = $startDate->copy()->addWeeks(5)->endOfWeek(Carbon::FRIDAY);

        // Generate the period
        $period = CarbonPeriod::create($startDate, $endDate)->filter(function($date){
            return $date->isWeekday();
        });

        // Truncate the 'sessionhour' table
        SessionHour::truncate();
        ModifiedHour::truncate();
        Appt::whereNull('patient_id')->where('status_id','=',0)->forceDelete();
        \Log::info("Seeder: Emptied session_hour table");

        // Insert the weekdays into the database using Eloquent
        foreach ($period as $date) {
            $officeHours = Officehour::where('dayslot', $date->dayOfWeekIso)->get();

            foreach ($officeHours as $officeHour) {
                SessionHour::create([
                    'date' => $date->toDateString(),
                    'open' => $officeHour->is_closed ? null : Carbon::parse($date->toDateString() . ' ' . $officeHour->open),
                    'close' => $officeHour->is_closed ? null : Carbon::parse($date->toDateString() . ' ' . $officeHour->close),
                    'is_closed' => (bool)$officeHour->is_closed,
                    'nps' => $officeHour->nps,
                    'officehour_id' => $officeHour->id,
                    'doctor_id' => $officeHour->doctor_id,
                    'header' => $this->getSessionHeader($officeHour),
                    'reason' => null,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        \Log::info("Seeded session_hour table");

        ModifiedHour::create([
            'date' => '2024-08-22',
            'open' => null,
            'close' => null,
            'is_closed' => true,
            'nps' => false,
            'officehour_id' => 4,
            'doctor_id' => 1,
            'reason' => 'Personal',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        \Log::info('Modified Hour Added');
    }

    private function getSessionHeader($hour): string
    {
        $closed = $hour->reason ? 'CLOSED: ' . $hour->reason : 'CLOSED';
        $hours = 'Hours: ' . Carbon::parse($hour->open)->format('h:i A') . ' - ' . Carbon::parse($hour->close)->format('h:i A');
        return $hour->is_closed ? $closed : $hours;
    }
}
