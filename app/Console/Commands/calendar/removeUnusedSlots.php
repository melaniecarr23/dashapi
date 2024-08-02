<?php

namespace App\Console\Commands\calendar;

use App\Models\Appt;
use Carbon\Carbon;
use Illuminate\Console\Command;

class removeUnusedSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:UnusedSlots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $thresholdDate = Carbon::now();
        $this->comment('Threshold Date: ' . $thresholdDate);

        // Retrieve the records first to debug
        $appointments = Appt::unbookedPast($thresholdDate)->get();
        $this->comment('Appointments to be deleted: ' . $appointments->count());

        // If you see the correct records, then proceed to delete
        if ($appointments->count() > 0) {
            Appt::unbookedPast($thresholdDate)->forceDelete();
            $this->comment('Records deleted successfully.');
        } else {
            $this->comment('No records found to delete.');
        }
    }
}
