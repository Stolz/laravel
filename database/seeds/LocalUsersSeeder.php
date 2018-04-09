<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userRepository = app(App\Repositories\Contracts\UserRepository::class);
        $roleRepository = app(App\Repositories\Contracts\RoleRepository::class);

        $users = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'role' => $roleRepository->findBy('name', 'Admin')],
            ['name' => 'User', 'email' => 'user@example.com', 'role' => $roleRepository->findBy('name', 'User')],
        ];

        foreach ($users as $user)
            $userRepository->create(App\Models\User::make($user)->setPassword('secret'));
    }
}
