<?php

namespace App\Repositories;

class UserRepositoryViaCapsule extends SoftDeletableModelRepositoryViaCapsule implements Contracts\UserRepository
{
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
