<?php

namespace App\Repositories\QueryBuilder;

use App\Repositories\Contracts\UserRepository as UserRepositoryContract;
use App\Repositories\Traits\UserProvider;

class UserRepository extends SoftDeletableModelRepository implements UserRepositoryContract
{
    use UserProvider;

    /**
     * Database table to use.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Convert database record into domain model.
     *
     * @param  \StdClass $record
     * @return \App\Models\User
     */
    protected function recordToModel(\StdClass $record)
    {
        return \App\Models\User::make((array) $record);
    }
}
