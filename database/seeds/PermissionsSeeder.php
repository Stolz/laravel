<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $permissionRepository = app(App\Repositories\Contracts\PermissionRepository::class);

        $permissions = [
            ['name' => 'user-list'],
            ['name' => 'user-view'],
            ['name' => 'user-create'],
            ['name' => 'user-update'],
            ['name' => 'user-delete'],
        ];

        foreach ($permissions as $permission)
            $permissionRepository->create(App\Models\Permission::make($permission));
    }
}
