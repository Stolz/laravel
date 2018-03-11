<?php

namespace App\Repositories;

use App\Models\Model;
use App\Repositories\Contracts\SoftDeletableModelRepository;

abstract class SoftDeletableModelRepositoryViaCapsule extends ModelRepositoryViaCapsule implements SoftDeletableModelRepository
{
    /**
     * Whether or not include soft deleted models in the next query.
     *
     * @var bool
     */
    protected $withSoftDeleted = false;

    /**
     * Begin a new query on the model table with soft delete support.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function softDeleteAwareQuery(): \Illuminate\Database\Query\Builder
    {
        $query = $this->query();

        if ($this->withSoftDeleted) {
            $this->withSoftDeleted = false; // Ensure the flag is used only once per query
        } else {
            $query->whereNull('deleted_at');
        }

        return $query;
    }

    // Contract ====================================================================

    /**
     * Include soft deleted models in operations that normally exclude them.
     *
     * @return \App\Repositories\Contracts\SoftDeletableModelRepository
     */
    public function includeSoftDeleted(): SoftDeletableModelRepository
    {
        $this->withSoftDeleted = true;

        return $this;
    }

    /**
     * Soft delete a model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        $deletedAt = $model->getDeletedAt();
        $updated = $this->update($model->setDeletedAt(now()));

        if (! $updated)
            $model->setDeletedAt($deletedAt);

        return $updated;
    }

    /**
     * Force delete a model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function forceDelete(Model $model): bool
    {
        return parent::delete($model);
    }

    /**
     * Restore a soft deleted model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function restore(Model $model): bool
    {
        $deletedAt = $model->getDeletedAt();
        $updated = $this->update($model->setDeletedAt(null));

        if (! $updated) {
            $model->setDeletedAt($deletedAt);
        }

        return $updated;
    }

    /**
     * Retrieve a single model by a given unique field.
     *
     * @param string $field
     * @param mixed  $value
     * @return \App\Models\Model|null
     */
    public function findBy($field, $value): ?Model
    {
        $found = $this->softDeleteAwareQuery()->where($field, $value)->first();

        return ($found) ? $this->recordToModel($found) : null;
    }

    /**
     * Retrieve all models.
     *
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(): \Illuminate\Support\Collection
    {
        return $this->softDeleteAwareQuery()->get()->transform(function ($record) {
            return $this->recordToModel($record);
        });
    }

    /**
     * Count the number of models.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->softDeleteAwareQuery()->count();
    }
}
