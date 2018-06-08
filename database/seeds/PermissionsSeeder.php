<?php

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepository;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $permissionRepository = app(PermissionRepository::class);

        $permissions = [
            ['name' => 'user-list'],
            ['name' => 'user-view'],
            ['name' => 'user-create'],
            ['name' => 'user-update'],
            ['name' => 'user-delete'],
        ];

        foreach ($permissions as $permission)
            $permissionRepository->create(Permission::make($permission));
    }
}
