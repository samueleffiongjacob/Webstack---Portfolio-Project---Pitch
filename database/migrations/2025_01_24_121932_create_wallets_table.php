<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id('wallet_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email');
            $table->unsignedBigInteger('wallet_type_id'); // Foreign key
            $table->unique(['user_id', 'wallet_type_id'], 'user_wallet_unique');
            $table->string('wallet_type', 100);
            // $table->decimal('balance', 10, 2)->default(0.00);
            $table->decimal('balance', 10, 2)->default(0.00)->check('balance >=0');

            // Add foreign keys only if referenced tables exist
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            }

            if (Schema::hasTable('wallet_types')) {
                $table->foreign('wallet_type_id')->references('wallet_type_id')->on('wallet_types')->onDelete('cascade')->onUpdate('cascade');
            }


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
