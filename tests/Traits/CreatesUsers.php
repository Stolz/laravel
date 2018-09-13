<?php

namespace Tests\Traits;

use App\Models\Role;
use App\Models\User;

trait CreatesUsers
{
    /**
     * Create a test user.
     *
     * @param  array $userAttributes
     * @param  array $roleAttributes
     * @return \App\Models\User
     */
    protected function createUser(array $userAttributes = [], array $roleAttributes = []): User
    {
        // Create role if none was provided
        if (! isset($userAttributes['role'])) {
            $userAttributes['role'] = factory(Role::class)->make($roleAttributes);
            $this->roleRepository->create($userAttributes['role']);
        }

        // Create user
        $user = factory(User::class)->make($userAttributes);
        $this->userRepository->create($user);

        return $user;
    }
}
