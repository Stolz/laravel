<?php

use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Database\Seeder;

class LocalUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Get all roles
        $roles = app(RoleRepository::class)->all();

        // Add a user for each role
        $userRepository = app(UserRepository::class);
        foreach ($roles as $role) {
            $user = factory(User::class)->make([
                'name' => $name = $role->getName(),
                'email' => str_slug($name, '.') . '@example.com',
                'role' => $role
            ]);

            $userRepository->create($user);
        }
    }
}
