<?php

use App\Models\User;

class LocalUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Add a user for each role
        $roles = $this->roleRepository->all();
        foreach ($roles as $role) {
            $user = factory(User::class)->make([
                'name' => $name = $role->getName(),
                'email' => str_slug($name, '.') . '@example.com',
                'role' => $role
            ]);

            $this->userRepository->create($user);
        }
    }
}
