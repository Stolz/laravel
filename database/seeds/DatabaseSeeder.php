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

        // Regular seeds
        $this->call('PermissionsSeeder');
        $this->call('RolesSeeder');

        // Seeds for local environment.
        if (app()->environment('local')) {
            $this->call('LocalUsersSeeder');
        }
    }
}
