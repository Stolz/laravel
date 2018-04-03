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
}
