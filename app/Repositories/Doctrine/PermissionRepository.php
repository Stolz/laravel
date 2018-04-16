<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\PermissionRepository as PermissionRepositoryContract;

class PermissionRepository extends ModelRepository implements PermissionRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = \App\Models\Permission::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'p';
}
