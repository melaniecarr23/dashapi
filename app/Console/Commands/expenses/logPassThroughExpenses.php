<?php

namespace App\Console\Commands\expenses;

use Illuminate\Console\Command;

class logPassThroughExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ogPassThroughExpenses {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log Expenses and 1099 Wages CREDITED and DUE from the Invoice';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $invoice = Invoice::with('items')->whereId($id)->first();
        // pass through expenses for mel, advertising expenses for Dr. Dave
        $advertising = $invoice->items->filter(function($item) {
            return $item->doctor_id == 2;
        });

        // 1099 contracted expense for Melanie
        $wages = $invoice->items->filter(function($item) {
            return $item->doctor_id == 1;
        });

        // log pass through expenses
        $passThrough = new Expense([
            'name' => 'Pass Through Expenses',
            'expense_category_id' => 18,
            'due_amt' => $advertising->sum('amount'),
            'pmt_amt' => $advertising->sum('amount'),
            'due_date' => $invoice['created_at']->addDays(7),
            'paid_date' =>$invoice['created_at'],
            'description'=>'Expenses shared with Dr. Dave',
            'pmt_type' => 'Credit',
            'invoice_id' => $invoice->id,
            'doctor_id' => 1
        ]);
        $passThrough->save();

        // LOG WAGES PAID AS MIXED (linked to invoice)
        $wageCredited = new Expense([
            'name' => 'David J. Thomas, D.C.',
            'expense_category_id' => 12,
            'due_amt' => $wages->sum('amount'),
            'pmt_amt'=> $wages->sum('amount'),
            'due_date' =>$invoice['created_at']->addDays(7),
            'paid_date' =>$invoice['created_at'],
            'description'=>'1099 Contracted',
            'pmt_type' => 'Mixed',
            'invoice_id' => $invoice->id,
            'doctor_id' => 1
        ]);
        $wageCredited->save();

        // Daily Expense share listed as advertising for dr dave
        $advertising = new Expense([
            'name' => 'Melanie Carr, D.C.',
            'expense_category_id' => 1,
            'due_amt' => $advertising->sum('amount'),
            'pmt_amt' => $advertising->sum('amount'),
            'due_date' =>$invoice['created_at']->addDays(7),
            'paid_date' =>$invoice['created_at'],
            'description'=>'Daily Expense Share',
            'pmt_type' => 'Credit',
            'invoice_id' => $invoice->id,
            'doctor_id' => 2
        ]);
        $advertising->save();

        // LOG WHAT WAS PAID AS SERVICES FOR DR DAVE
        if($invoice->payer_id == 1 && $invoice->total_due > 0) {
            $payment = new Payment([
                'pmt_type' => 'Square',
                'due_amt'=>$invoice->total_due,
                'pmt_note' => 'Invoice: '. $invoice->id,
                'patient_id' => 1,
                'pmt_paid' => 0,
                'doctor_id'=> 2,
                'pmt_category' => 'Services'
            ]);
            $payment->save();
        }
        // LOG WHAT IS STILL DUE
    }
}
