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
        Schema::rename('appt','appointment');
        Schema::table('appointment', function (Blueprint $table) {

            // Renaming columns
            $table->renameColumn('appt_note', 'note');
            $table->renameColumn('appt_type_id', 'type_id');
            $table->renameColumn('appt_status_id', 'status_id');
            $table->renameColumn('objective_text', 'objective');

            // Dropping unnecessary columns
            $table->dropColumn('appt_date');
            $table->dropColumn('appt_time');
            $table->dropColumn('appt_reminder');
            $table->dropColumn('reminder_cell');
            $table->dropColumn('clearUC');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appt', function (Blueprint $table) {
            // Reverting changes
            $table->dropColumn('end_time');
            $table->renameColumn('note', 'appt_note');
            $table->renameColumn('type_id', 'appt_type_id');
            $table->renameColumn('status_id', 'appt_status_id');
            $table->renameColumn('objective', 'objective_text');
            $table->renameColumn('date', 'date_time');
            $table->renameColumn('time', 'appt_time');

            // Adding dropped columns
            $table->boolean('appt_reminder')->nullable();
            $table->string('reminder_cell')->nullable();
        });
    }
};
