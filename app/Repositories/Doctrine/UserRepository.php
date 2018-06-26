<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\UserRepository as UserRepositoryContract;
use App\Repositories\Traits\UserProvider;

class UserRepository extends SoftDeletableModelRepository implements UserRepositoryContract
{
    use UserProvider;

    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = \App\Models\User::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'user';

    /**
     * Retrieve the users of the given role.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Support\Collection of \App\Models\User
     */
    public function allFromRole(\App\Models\Role $role): \Illuminate\Support\Collection
    {
        return collect($this->repository->findBy(['role' => $role]));
    }
}
