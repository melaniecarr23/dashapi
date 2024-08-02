<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('payment', function (Blueprint $table) {
            // Rename columns for clarity and simplicity
            $table->renameColumn('paid_date', 'date');
            $table->renameColumn('pmt_duedate', 'due_date');
            $table->renameColumn('due_amt', 'due_amount');
            $table->renameColumn('pmt_note', 'note');
            $table->renameColumn('pmt_type', 'method');
            $table->renameColumn('pmt_amt', 'amount');
            $table->renameColumn('pmt_paid', 'paid');
            $table->double('discount');

            // drop columns
            $table->dropColumn(['checkoutId','orderId','referenceId','transactionId','checkoutUrl','receiptUrl']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment', function (Blueprint $table) {
            // Revert the column names
            $table->renameColumn('pmt_date', 'date');
            $table->renameColumn('pmt_duedate', 'due_date');
            $table->renameColumn('pmt_method', 'method');
            $table->renameColumn('pmt_amount', 'amount');
            $table->renameColumn('pmt_status', 'status');
        });
    }
};
