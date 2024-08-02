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
        Schema::rename('appt_type', 'type');
        Schema::table('type', function (Blueprint $table) {
            $table->renameColumn('appt_type_name', 'name');
            $table->renameColumn('appt_abbr', 'abbr');
            $table->renameColumn('amount', 'amount')->nullable();
            $table->renameColumn('appt_code', 'code')->nullable(0);
            $table->renameColumn('appt_type_length', 'length')->nullable();
            $table->string('model_name');
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
