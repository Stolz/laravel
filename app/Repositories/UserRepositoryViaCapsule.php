<?php

namespace App\Repositories;

class UserRepositoryViaCapsule extends ModelRepositoryViaCapsule implements Contracts\UserRepository
{
    /**
     * Database table to use.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Whether or not the model handled by this repository supports soft deletes.
     *
     * @var bool
     */
    protected $softDeletable = true;

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
