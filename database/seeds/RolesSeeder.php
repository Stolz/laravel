<?php

use App\Models\Role;
use App\Repositories\Contracts\RoleRepository;
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
        $roleRepository = app(RoleRepository::class);

        $roles = [
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];

        foreach ($roles as $role)
            $roleRepository->create(Role::make($role));
    }
}

