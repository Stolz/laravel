<?php

namespace App\Repositories\Contracts;

use App\Models\Model;

interface SoftDeletableModelRepository extends ModelRepository
{
    /**
     * Include soft deleted models in operations that normally exclude them.
     *
     * @return \App\Repositories\Contracts\SoftDeletableModelRepository
     */
    public function includeSoftDeleted(): SoftDeletableModelRepository;

    /**
     * Force delete a model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function forceDelete(Model $model): bool;

    /**
     * Restore a soft deleted model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function restore(Model $model): bool;
}
