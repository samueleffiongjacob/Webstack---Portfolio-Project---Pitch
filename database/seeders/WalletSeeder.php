<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Fetch all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed the users table first.');
            return;
        }
        // Loop through each user and create wallets
        $users->each(function ($user) {
            // Create multiple wallets for each user
            $user->wallets()->createMany([
                [
                    'wallet_type' => 'savings',
                    'balance' => 500.00
                ],
                [
                    'wallet_type' => 'current',
                    'balance' => 1000.00
                ],
            ]);
        });
        
        $this->command->info('Wallets seeded successfully.');
    }
}
