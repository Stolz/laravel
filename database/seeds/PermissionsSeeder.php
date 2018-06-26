<?php

use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            /// Access module
            ['name' => 'use-access-module'],

            // User
            ['name' => 'user-list'],
            ['name' => 'user-view'],
            ['name' => 'user-create'],
            ['name' => 'user-update'],
            ['name' => 'user-delete'],

            /// Master module
            ['name' => 'use-master-module'],

            // Country
            ['name' => 'country-list'],
            ['name' => 'country-view'],
            ['name' => 'country-create'],
            ['name' => 'country-update'],
            ['name' => 'country-delete'],
        ];

        foreach ($permissions as $permission) {
            $permission = Permission::make($permission);
            $this->permissionRepository->create($permission);
        }
    }
}
