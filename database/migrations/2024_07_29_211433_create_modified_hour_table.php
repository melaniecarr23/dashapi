<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('closed', 'modified_hour');
        Schema::table('modified_hour', function (Blueprint $table) {
            $table->renameColumn('closed_date','date');
            $table->dropColumn('AM_PM_DAY');
            $table->dropColumn('closed');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modified_hour');
    }
};
