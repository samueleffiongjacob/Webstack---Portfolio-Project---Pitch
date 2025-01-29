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
        Schema::create('wallet_types', function (Blueprint $table) {
            $table->id('wallet_type_id');
            $table->string('type_name', 50)->unique();
            // $table->string('type')->unique();
            $table->decimal('min_balance', 10, 2);
            $table->decimal('monthly_interest_rate', 5, 2);
            $table->timestamps();
        });

        // Insert default wallet types
        DB::table('wallet_types')->insert([
            ['type_name' => 'Default', 'min_balance' => 0.00, 'monthly_interest_rate' => 0.05, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Classic Savings', 'min_balance' => 100.00, 'monthly_interest_rate' => 0.10, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Premium Saving', 'min_balance' => 200.00, 'monthly_interest_rate' => 0.10, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Premium Current', 'min_balance' => 500.00, 'monthly_interest_rate' => 0.10, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_types');
    }
};
