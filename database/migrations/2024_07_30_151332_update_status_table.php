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
        Schema::rename('appt_status', 'status');
        Schema::table('status', function (Blueprint $table) {
            $table->renameColumn('status', 'name');
            $table->string('model_name')->nullable()->default('appointment'); // Add this column to determine the model type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {

        Schema::table('status', function (Blueprint $table) {
            $table->dropColumn('model_type');
            $table->renameColumn('name', 'status');
        });
        Schema::rename('status', 'appt_status');
    }
};
