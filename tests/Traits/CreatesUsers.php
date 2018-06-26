<?php

namespace Tests\Traits;

use App\Models\Role;
use App\Models\User;
use App\Traits\AttachesRepositories;

trait CreatesUsers
{
    use AttachesRepositories;

    /**
     * Create a test user.
     *
     * @param  array $attributes
     * @return \App\Models\User
     */
    protected function createUser(array $attributes = []): User
    {
        // Create test role if none was provided
        if (! isset($attributes['role'])) {
            $attributes['role'] = Role::make(['name' => str_random(6)]);
            $this->roleRepository->create($attributes['role']);
        }

        // Create test user
        $user = factory(User::class)->make($attributes);
        $this->userRepository->create($user);

        return $user;
    }
}
