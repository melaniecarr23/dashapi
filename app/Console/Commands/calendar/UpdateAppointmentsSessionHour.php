<?php

namespace App\Console\Commands\calendar;

use App\Models\Appt;
use App\Models\SessionHour;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateAppointmentsSessionHour extends Command
{
    protected $signature = 'appointments:update-session-hour';
    protected $description = 'Update appointments with the correct session_hour_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get future session hours
        $sessionHours = SessionHour::where('date', '>=', Carbon::now())->get();

        foreach ($sessionHours as $sessionHour) {
            $start = Carbon::parse($sessionHour->open)->subMinute();
            $end = Carbon::parse($sessionHour->close)->addMinute();
            // Find appointments within this session hour's time range
            $appointments = Appt::whereBetween('date_time', [$start, $end])
                ->get();

            foreach ($appointments as $appointment) {
                $appointment->session_hour_id = $sessionHour->id;
                $appointment->save();
            }
        }

        $this->info('Appointments updated with session_hour_id.');
    }
}
