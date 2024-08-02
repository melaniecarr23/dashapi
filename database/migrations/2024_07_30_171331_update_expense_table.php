<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('expense',function(Blueprint $table){
            $table->renameColumn('expense_category_id','category_id');
            $table->renameColumn('due_amt','due_amount');
            $table->renameColumn('pmt_amt','paid_amount');
            $table->renameColumn('pmt_type','method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
