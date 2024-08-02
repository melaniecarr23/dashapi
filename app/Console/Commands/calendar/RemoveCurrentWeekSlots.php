<?php

namespace App\Console\Commands\calendar;

use App\Models\Day;
use App\Models\Officehour;
use App\Models\SessionHour;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveCurrentWeekSlots extends Command
{
    protected $signature = 'remove:current-week-slots';
    protected $description = 'Remove current week slots after office hours end on Friday';

    public function handle()
    {
        $now = Carbon::now();
        $lastOfficeHour = Officehour::where('publish', '=', 1)
            ->orderBy('dayslot', 'desc')
            ->with(['sessionHours' => function ($query) {
                $query->orderBy('close', 'desc');
            }])
            ->first();
        $cutoffDate = $lastOfficeHour->sessionHour->close;
        // Determine the start of the week based on the current time
        if($now->gte($cutoffDate)) {
            // Remove session hours and days of the current week
            SessionHour::whereDate('date', '<=',$cutoffDate)->delete();
            Day::whereDate('day', '<=',$cutoffDate)->delete();
        }

        $this->info('Current week\'s dates and session hours removed successfully.');
    }
}
