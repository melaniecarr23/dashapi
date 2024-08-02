<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\notifications\queueReminders::class
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // COMMANDS FOR INVOICING
//        $schedule->command('expenseToday')->dailyAt('21:00');
//        $schedule->command('weeklyInvoice')->weeklyOn(1,'09:00');
//        $schedule->command('logPassThroughExpenses')->weeklyOn(1,'09:15');

        // APPOINTMENT COMMANDS
        // add days and session_hours
        $schedule->command('open:slots')->dailyAt('2:00');
        // remove unbooked appointments immediately
        $schedule->command('remove:UnusedSlots')->everyFiveMinutes();
        // remove finished week session hours and days
        $schedule->command('remove:current-week-slots')->hourly()->when(function () {
            $now = Carbon::now();
            return $now->isFriday() && $now->hour >= 10 && $now->hour <= 19;
        });

        // MESSAGING COMMANDS
        // remove this once switched over fully to using date_time for appointments
//        $schedule->command('updateAppts')->twiceDailyAt(6,18);
        $schedule->command('queue:reminders')->twiceDailyAt(7,19);
//        $schedule->command('emptyTextQueue')->everyMinute();
//        $schedule->command('emptyVoiceQueue')->everyMinute();
//        $schedule->command('updateTwilioStatus')->everyFiveMinutes();

        // SQUARE COMMANDS
//        $schedule->command('getPayments')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
