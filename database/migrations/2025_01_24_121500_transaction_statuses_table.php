<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'transaction_statuses' table
        Schema::create('transaction_statuses', function (Blueprint $table) {
            $table->id('status_id');
            $table->string('status_name', 50)->unique();
        });

        // Populate default statuses
        DB::table('transaction_statuses')->insert([
            ['status_name' => 'PENDING'],
            ['status_name' => 'SUCCESS'],
            ['status_name' => 'FAILED'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_statuses');
    }
};
