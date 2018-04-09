<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\RoleRepository as RoleRepositoryContract;

class RoleRepository extends ModelRepository implements RoleRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = \App\Models\Role::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'r';
}
