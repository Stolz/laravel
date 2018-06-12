<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Clear cache
        Artisan::call('cache:clear');
        Artisan::call('doctrine:clear:metadata:cache');
        Artisan::call('doctrine:clear:query:cache');
        Artisan::call('doctrine:clear:result:cache');

        // Regular seeds
        $this->call([
            'CountriesSeeder',
            'PermissionsSeeder',
            'RolesSeeder',
        ]);

        // Seeds for local environment
        if (app()->environment('local')) {
            $this->call([
                'LocalUsersSeeder',
                'LocalNotificationsSeeder',
            ]);
        }
    }
}
