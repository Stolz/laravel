<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roleRepository = app(App\Repositories\Contracts\RoleRepository::class);

        $roles = [
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];

        foreach ($roles as $role)
            $roleRepository->create(App\Models\Role::make($role));
    }
}
