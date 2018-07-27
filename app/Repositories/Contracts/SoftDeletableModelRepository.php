<?php

namespace App\Repositories\Contracts;

use App\Models\Model;

interface SoftDeletableModelRepository extends ModelRepository
{
    /**
     * Whether or not include soft deleted models in next operation.
     *
     * @param  bool $includeSoftDeleted
     * @return \App\Repositories\Contracts\SoftDeletableModelRepository
     */
    public function includeSoftDeleted(bool $includeSoftDeleted = true): SoftDeletableModelRepository;

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
