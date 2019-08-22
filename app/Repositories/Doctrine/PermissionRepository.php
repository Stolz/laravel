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
    protected $class = \App\Models\Permission::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $alias = 'permission';

   /**
    * Synchronize permissions.
    *
    * @param \Illuminate\Support\Collection $permissions of \App\Models\Permission
    * @return bool
    */
    public function sync(\Illuminate\Support\Collection $permissions): bool
    {
        if ($permissions->isEmpty()) {
            return false;
        }

        $success = true;
        $names = [];

        // Update existing permissions and create new permissions
        foreach ($permissions as $permission) {
            $names[] = $name = $permission->getName();
            $old = $this->findBy('name', $name);
            if ($old) {
                $old->setDescription($permission->getDescription());
                $success = $this->update($old) and $success;
            } else {
                $success = $this->create($permission) and $success;
            }
        }

        // Remove obsolete permissions
        $queryBuilder = $this->getQueryBuilder();
        $condition = $queryBuilder->expr()->notIn("{$this->alias}.name", ':names');
        $obsolete = $queryBuilder->where($condition)->setParameter('names', $names)->getQuery()->getResult();
        foreach ($obsolete as $permission) {
            $this->delete($permission);
        }

        return $success;
    }
}
