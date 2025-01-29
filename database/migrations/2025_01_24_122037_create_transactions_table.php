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
        // Create the 'transactions' table
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('sender_wallet_id');
            $table->foreignId('receiver_wallet_id');
            // $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED'])->default('SUCCESS');
            $table->unsignedBigInteger('status_id');
            $table->decimal('amount', 10, 2);

            // Define foreign key constraints referencing 'wallet_id'
            $table->foreign('sender_wallet_id')
                ->references('wallet_id')->on('wallets')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('receiver_wallet_id')
                ->references('wallet_id')->on('wallets')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('status_id')
                ->references('status_id')->on('transaction_statuses')
                ->onDelete('restrict')->onUpdate('cascade'); // Restrict deletion of statuses in use
            // time stamp
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
