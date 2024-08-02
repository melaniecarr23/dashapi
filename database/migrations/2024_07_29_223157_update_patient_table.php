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
        Schema::table('patient', function (Blueprint $table) {
            // Adding fields for notification preferences
            $table->string('preferred_contact_method')->nullable()->after('email');
            $table->boolean('text_notifications')->default(false)->after('preferred_contact_method');
            $table->boolean('email_notifications')->default(false)->after('text_notifications');
            $table->boolean('call_notifications')->default(false)->after('email_notifications');

            // Rename columns for consistency and clarity
            $table->renameColumn('list','listing');

            // Dropping unnecessary columns
            $table->dropColumn('cc');
            $table->dropColumn('recall');
            $table->dropColumn('recall_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient', function (Blueprint $table) {
            // Reverting changes
            $table->dropColumn('preferred_contact_method');
            $table->dropColumn('text_notifications');
            $table->dropColumn('email_notifications');
            $table->dropColumn('call_notifications');

            $table->renameColumn('home_phone', 'home');
            $table->renameColumn('work_phone', 'work');
            $table->renameColumn('cell_phone', 'cell');

            $table->string('cc')->nullable();
            $table->boolean('recall')->nullable();
        });
    }
};
