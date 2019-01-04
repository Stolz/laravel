<?php

use App\Models\Role;

class LocalRolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create a role for developers ...
        $role = Role::make(['name' => 'Developer']);
        $this->roleRepository->create($role);

        // ... and assign all the permissions
        $permissions = $this->permissionRepository->all();
        $this->roleRepository->replacePermissions($role, $permissions);
    }
}
