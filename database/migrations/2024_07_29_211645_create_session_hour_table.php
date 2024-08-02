<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('session_hour', function (Blueprint $table) {
            $table->renameColumn('session_date','date');
            $table->renameColumn('closed','is_closed');
            $table->string('header');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_hours');
    }
};
