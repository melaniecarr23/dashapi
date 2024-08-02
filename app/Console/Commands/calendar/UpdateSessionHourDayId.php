<?php

namespace App\Console\Commands\calendar;

use App\Models\Day;
use App\Models\SessionHour;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateSessionHourDayId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessionHour:updateWithDayID';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch all session hours
        $sessionHours = SessionHour::all();

        foreach ($sessionHours as $sessionHour) {
            // Extract the date from the start_time
            $date = Carbon::parse($sessionHour->date)->toDateString();

            // Find the corresponding day record
            $day = Day::where('day', $date)->first();

            if ($day) {
                // Update the day_id in the session_hour table
                $sessionHour->day_id = $day->id;
                $sessionHour->save();
            }
        }

        $this->info('SessionHour table updated with day_id.');

        return 0;
    }
}
