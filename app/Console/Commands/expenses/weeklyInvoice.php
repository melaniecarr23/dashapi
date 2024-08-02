<?php

namespace App\Console\Commands\expenses;

use Illuminate\Console\Command;

class weeklyInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weeklyInvoice';

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
        $doctors = User::all();
        $drcarr = $doctors->first();
        $drdave = $doctors->last();
        // get expenses for each doctor
        $drcarr->items = InvoiceItem::where('doctor_id',1)->whereNull('invoice_id')
            ->where('name','Chiropractor Services')->get();
        $drdave->items = InvoiceItem::where('doctor_id',2)->whereNull('invoice_id')
            ->where('description','Web site, text, branding, etc.')->get();
        // get total due for each doctor
        $drcarr->due = $drcarr->items->sum('due_amt') - $drdave->items->sum('due_amt');
        $drdave->due = $drdave->items->sum('due_amt') - $drcarr->items->sum('due_amt');
        // determine payer and payee
        $payer = $drcarr->due >= 0 ? $drcarr : $drdave;
        // set items as debit or credit
        $payer->items->each(function($item) {
            $item->type = 'debit';
        });
        // set items as debit or credit
        $payee = $payer == $drdave ? $drcarr: $drdave;
        $payee->items->each(function($item) {
            $item->type = 'credit';
        });

        // merge the items together
        $items = $payee->items->merge($payer->items);

        // create the invoice
        if($payer->items->count() > 0 || $payee->items->count() > 0) {
            $invoice = new Invoice([
                'payer_id' =>$payer->id,
                'payee_id' => $payee->id,
                'expense_total'=>abs($payer->items->sum('amount')),
                'credits_total'=>abs($payee->items->sum('amount')),
                'total_due' => $payer->items->sum('amount') - $payee->items->sum('amount'),
                'paid' => false,
                'pmt_type'=> null,
                'pmt_amt'=> 0
            ]);
            $invoice->save();
            // ASSIGN ITEMS TO THE INVOICE
            $items->each(function($item) use ($invoice) {
                $item->invoice_id = $invoice->id;
                $item->save();
            });

            // Text the payer
            $twilio1 =  new Twilio([
                'sender' => $payee->sender,
                'number' => $payer->phone,
                'message' => 'New Invoice Due: https://ihcdocdash.com/invoices/'.$invoice->id
                    . "\n Pay Here: ". $payee->square_link."\n",
                'status'=> 'Pending'
            ]);
            $twilio1->save();
            $twilio2 =  new Twilio([
                'sender' => $payer->sender,
                'number' => $payee->phone,
                'message' => 'View Invoice: https://ihcdocdash.com/invoices/'.$invoice->id,
                'status'=> 'Pending'
            ]);
            $twilio2->save();

            $this->call('logPassThroughExpenses',[
                'id'=> $invoice['id']
            ]);
        }
    }
}
