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
        Schema::table('plan', function (Blueprint $table) {
            // Renaming existing fields
            $table->renameColumn('plan', 'name');
            $table->renameColumn('planamt', 'amount');

            // Adding new fields
            $table->integer('member_limit')->nullable()->after('amount');
            $table->decimal('additional_member_cost', 8, 2)->nullable()->after('member_limit');

            // Dropping unused columns
            $table->dropColumn(['square_catalog_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('plan', function (Blueprint $table) {
            // Reverting the renamed fields back to their original names


            // Dropping the added fields
            $table->dropColumn('member_limit');
            $table->dropColumn('additional_member_cost');
            $table->dropColumn('square_catalog_id');
            $table->dropColumn('url');

            $table->renameColumn('plan','name');
            $table->renameColumn('planamt','amount');

            // Optionally re-add the dropped columns if needed (provide the correct types if needed)
            // $table->string('square_catalog_id')->nullable();
            // $table->string('url')->nullable();
        });
    }
};
