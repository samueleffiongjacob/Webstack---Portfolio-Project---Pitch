<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
// use App\Models\User;
// use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Retrieve credentials from the environment variables
        // $dbName = config('database.connections.mysql.database');

        // if(!empty($dbName)) {
        //     DB::statement("CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        //     // Ensures string compatibility
        //     Schema::defaultStringLength(191);
        // }

        // Set default string length for compatibility (if needed)
        Schema::defaultStringLength(191);

        // Create the database in non-production environments
        if (App::environment(['local', 'testing'])) {
            $dbName = config('database.connections.mysql.database');

            if (!empty($dbName)) {
                DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
        }

        // Register the UserObserver
        // User::observe(UserObserver::class);
    }
}
