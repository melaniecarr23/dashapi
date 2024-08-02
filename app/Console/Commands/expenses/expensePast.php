<?php

namespace App\Console\Commands\expenses;

use App\Models\Appt;
use App\Models\Officehour;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class expensePast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expensePast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice the doctors for services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = Carbon::create(2022,10,3,0,0,0);
        while($start <= Carbon::create(2022,10,9,0,0,0)) {
            $pmt =  Payment::with('patient')
                ->whereRelation('patient','doctor_id',2)
                ->where('pmt_type','Square')
                ->where('paid_date',$start->format('Y-m-d'))->sum('pmt_amt');
            // Calculate fee for days typically scheduled, even if no one checked out
            // agreed upon by Dr. Dave and Dr. Carr Tuesday 9/20/22
            $fee =  Officehour::where(['weekday'=>$start->dayName,'doctor_id'=>2])->count() > 0;
            $appts =  Appt::where(['doctor_id'=>2,'status_id'=>9,'date_time' => $start->format('Y-m-d')])->count();
//      // SAVE INVOICE ITEMS
            if($pmt > 0) {
                $expense = new InvoiceItem([
                    'name'=> 'Chiropractor Services',
                    'description' => 'Services Rendered '.$start->format('m-d-Y'),
                    'note' => null,
                    'invoice_id'=> null,
                    'amount' => $pmt,
                    'doctor_id' => 1,
                    'type'=>null
                ]);
                $expense->save();
            }
            if($fee == 1) {
                $expense = new InvoiceItem([
                    'name'=> 'Expense Share',
                    'description' => 'Web site, text, branding, etc.',
                    'note'=> $start->format('m-d-Y'). ': '.$appts . ' appts',
                    'amount' => 60,
                    'invoice_id' => null,
                    'doctor_id' => 2,
                    'type'=> null
                ]);
                $expense->save();
            }
            $start = $start->copy()->addDay()->startOfDay();
        }
    }
}
