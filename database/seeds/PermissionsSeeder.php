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
            ['name' => 'user-list'],
            ['name' => 'user-view'],
            ['name' => 'user-create'],
            ['name' => 'user-update'],
            ['name' => 'user-delete'],
        ];

        foreach ($permissions as $permission) {
            $permission = Permission::make($permission);
            $this->permissionRepository->create($permission);
        }
    }
}
