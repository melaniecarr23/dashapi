<?php

namespace App\Console\Commands\billing;


use App\Models\Appt;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class addNewPmtsDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addNewPmtsDue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add payments due for new appointments';

    /**
     * Execute the console command.
     *
     * @return int|string
     */
    public function handle(): int|string
    {
        $appts = Appt::with('patient')
            ->where('created_at','>=',Carbon::now()->subMinute())->get();
        $appts->each(function($appt){
            // handle new patients
            if(in_array($appt->type_id,[3,4]) && $appt->type->amount > 0) {
                Payment::firstOrCreate(['patient_id' => $appt->patient_id, 'due_amt' => $appt->type->amount],['pmt_note' => $appt->note, 'pmt_duedate' => $appt->date_time,'doctor_id'=>$appt->doctor_id]);
            }
            if($appt->patient->parent->payment_day) {
                // handle existing
                $start = $this->getBillingDate($appt->patient->parent);
                // handle current billing cycle
                $this->addBilling($appt->patient->parent, $start, $start->copy()->addMonth()->subDay());
                // handle prior billing cycle
                $this->addBilling($appt->patient->parent, $start->copy()->subMonth(), $start->copy()->subDay());
                // handle upcoming billing cycle
                $this->addBilling($appt->patient->parent, $start->copy()->addMonth(), $start->copy()->addMonths(2)->subDay());
            }

        });
        $this->clearZeroDue();
        return 'Billing Updated!';
    }

    private function addBilling(mixed $parent, Carbon $start, $end): void
    {
        $active = Appt::whereIn('patient_id', $parent->family->pluck('id'))
                ->whereIn('status_id', [2, 9])
                ->whereNotIn('type_id', [3, 4])
                ->whereBetween('date_time', [$start->startOfDay(), $end->endOfDay()])
                ->get()->unique('patient_id')->count() > 0;
        // only bill them if active
        if ($active && $parent->plan->planamt > 0) {
            Payment::firstOrCreate(['patient_id' => $parent->id, 'pmt_duedate' => $start], ['due_amt' => $parent->plan->planamt,'doctor_id'=>$parent->doctor_id,'pmt_category' => 'Services']);
        }
    }
    private function getBillingDate($parent){
        return Carbon::now()->day($parent->payment_day)->isPast() || Carbon::now()->day($parent->payment_day)->isToday()
            ? Carbon::now()->day($parent->payment_day)->startOfDay()
            : Carbon::now()->day($parent->payment_day)->subMonth()->startOfDay();
    }

    private function clearZeroDue() {
        Payment::withTrashed()->where('due_amt','=',0)->whereNull('pmt_amt')->forceDelete();
        Payment::withTrashed()->where(['due_amt'=>0,'pmt_amt' => 0])->forceDelete();
    }

}
