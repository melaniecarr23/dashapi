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
        Schema::rename('posts','post');
        Schema::table('post', function (Blueprint $table) {
            $table->date('publish_date')->nullable()->after('body');
            $table->string('ai_prompt')->nullable()->after('publish_date');
            $table->string('slug')->nullable()->before('publish_date');
            $table->string('image_url')->nullable()->after('ai_prompt');
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
