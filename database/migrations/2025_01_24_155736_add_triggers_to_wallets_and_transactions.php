<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Trigger to enforce minimum balance
        DB::unprepared('
            CREATE TRIGGER enforce_min_balance BEFORE UPDATE ON wallets
            FOR EACH ROW
            BEGIN
                DECLARE min_balance DECIMAL(10, 2);
                SELECT min_balance INTO min_balance FROM wallet_types WHERE wallet_type_id = NEW.wallet_type_id;

                IF NEW.balance < min_balance THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Balance cannot fall below the minimum required for this wallet type.";
                END IF;
            END
        ');

        // Trigger to check sender's balance and update wallets
        DB::unprepared('
            CREATE TRIGGER check_sender_balance BEFORE INSERT ON transactions
            FOR EACH ROW
            BEGIN
                DECLARE sender_balance DECIMAL(10, 2);
                DECLARE min_balance DECIMAL(10, 2);

                -- Sender current balance
                SELECT balance INTO sender_balance
                FROM wallets
                WHERE wallet_id = NEW.sender_wallet_id;

                -- Sender wallet minimum balance requirement
                SELECT wt.min_balance INTO min_balance
                FROM wallet_types wt
                JOIN wallets w ON wt.wallet_type_id = w.wallet_type_id
                WHERE w.wallet_id = NEW.sender_wallet_id;
                
                -- sender must have enough balance to complete the transaction while maintaining the minimum balance
                IF sender_balance - NEW.amount < min_balance THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT =
                    "Insufficient Funds.";
                END IF;

                -- Deduct from sender and add to receiver
                UPDATE wallets SET balance = balance - NEW.amount
                WHERE wallet_id = NEW.sender_wallet_id;

                -- Add to receiver
                UPDATE wallets SET balance = balance + NEW.amount
                WHERE wallet_id = NEW.receiver_wallet_id;
            END
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        DB::unprepared('DROP TRIGGER IF EXISTS enforce_min_balance');
        DB::unprepared('DROP TRIGGER IF EXISTS check_sender_balance');
    }
};
