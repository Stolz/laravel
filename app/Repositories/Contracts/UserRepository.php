<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Auth\UserProvider;

interface UserRepository extends SoftDeletableModelRepository, UserProvider
{
    /**
     * Retrieve the users of the given role.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Support\Collection of \App\Models\User
     */
    public function allFromRole(\App\Models\Role $role): \Illuminate\Support\Collection;
}
