<?php

namespace App\Repositories\Contracts;

interface PermissionRepository extends ModelRepository
{
    /**
     * Synchronize permissions.
     *
     * @param \Illuminate\Support\Collection $permissions of \App\Models\Permission
     * @return bool
     */
    public function sync(\Illuminate\Support\Collection $permissions): bool;
}
