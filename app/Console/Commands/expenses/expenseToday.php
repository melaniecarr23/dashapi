<?php

namespace App\Console\Commands\expenses;

use Illuminate\Console\Command;

class expenseToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expenseToday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice Mel for Dave services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->startOfDay();
        $pmt =  Payment::with('patient')
            ->whereRelation('patient','doctor_id',2)
            ->where('pmt_type','Square')
            ->where('paid_date',$today->format('Y-m-d'))->sum('pmt_amt');
        // Calculate fee for days typically scheduled, even if no one checked out
        // agreed upon by Dr. Dave and Dr. Carr Tuesday 9/20/22
        $fee =  Officehour::where(['weekday'=>$today->dayName,'doctor_id'=>2,'fee'=>1])->count() > 0;
        $appts =  Appt::where(['doctor_id'=>2,'status_id'=>9,'date_time' => $today->format('Y-m-d')])->count();
        // expense Dr. Carr
        if($pmt > 0) {
            $expense = new InvoiceItem([
                'name'=> 'Chiropractor Services',
                'description' => 'Services Rendered '.$today->format('m-d-Y'),
                'note' => null,
                'invoice_id'=> null,
                'amount' => $pmt,
                'doctor_id' => 1,
                'type'=>null
            ]);
            $expense->save();
        }
        // expense Dr. Dave
        if($fee == 1) {
            $expense = new InvoiceItem([
                'name'=> 'Expense Share',
                'description' => 'Web site, text, branding, etc.',
                'note'=> $today->format('m-d-Y'). ': '.$appts . ' appts',
                'amount' => 60,
                'invoice_id' => null,
                'doctor_id' => 2,
                'type'=> null
            ]);
            $expense->save();
        }
    }
}
