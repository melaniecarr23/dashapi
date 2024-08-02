<?php

namespace App\Console\Commands\billing;

use App\Models\Appt;
use App\Models\Patient;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class runBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runBilling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bill active families that have not been billed';

    /**
     * Execute the console command.
     *
     * @return int|string
     */
    public function handle(): int|string
    {

        $families = Patient::with(['plan'])
            ->whereColumn('parent_id','id')
            ->whereRelation('plan','planamt','>',0)
            ->get();
        $families->each(function($pt){
            // get billing date
            $start = $this->getBillingDate($pt);
            // handle current billing cycle
            $this->addBilling($pt, $start, $start->copy()->addMonth()->subDay());
            // handle prior billing cycle
            $this->addBilling($pt, $start->copy()->subMonth(), $start->copy()->subDay());
            // remove billing for folks who were not here
            $this->removeBilling($pt, $start->copy()->subMonth(),$start->copy()->subDay());
            $this->removeBilling($pt, $start->copy()->subMonths(2),$start->copy()->subMonths()->subDay());
            // handle upcoming billing cycle
            $this->addBilling($pt, $start->copy()->addMonth(), $start->copy()->addMonths(2)->subDay());
        });
        return 'Billing Complete!';
    }

    private function addBilling(mixed $parent, Carbon $start, $end): void
    {
        $active = Appt::whereIn('patient_id', $parent->family->pluck('id'))
                ->whereIn('status_id', [2, 9])
                ->whereNotIn('type_id', [3, 4])
                ->whereBetween('date_time', [$start->startOfDay(), $end->endOfDay()])
                ->get()->unique('patient_id')->count() > 0;
        // only bill them if active
        if ($active) {
            Payment::firstOrCreate(['patient_id' => $parent->id, 'pmt_duedate' => $start], ['due_amt' => $parent->plan->planamt,'doctor_id'=>$parent->doctor_id]);
        }
    }
    private function getBillingDate($parent): Carbon
    {
        return Carbon::now()->day($parent->payment_day)->isPast() || Carbon::now()->day($parent->payment_day)->isToday()
            ? Carbon::now()->day($parent->payment_day)->startOfDay()
            : Carbon::now()->day($parent->payment_day)->subMonth()->startOfDay();
    }

    private function removeBilling(mixed $parent, Carbon $start, $end): void
    {
        $inactive = Appt::whereIn('patient_id', $parent->family->pluck('id'))
                ->whereIn('status_id', [2, 9])
                ->whereNotIn('type_id', [3, 4])
                ->whereBetween('date_time', [$start->startOfDay(), $end->endOfDay()])
                ->get()->unique('patient_id')->count() < 1;
        // only bill them if active
        if ($inactive) {
            $payment = Payment::where(['patient_id' => $parent->id, 'pmt_duedate' => $start])->where('pmt_amt','<',0.01)->delete();
        }
    }

}
