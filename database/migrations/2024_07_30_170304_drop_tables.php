<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // List of tables to drop
        $tables = [
            'categories',
            'comment',
            'calls',
            'articles',
            'objective',
            'objective',
            'family',
            'gender',
            'nikolag_customers',
            'pages',
            'scan',
            'square_catalog',
            'square_customer',
            'square_payment',
            'status_message',
            'tasks',
            'twilio'
            // Add more tables as needed
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * Reverse the migrations.
     */

};
