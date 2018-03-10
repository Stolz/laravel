<?php

namespace App\Repositories\Contracts;

use App\Models\Model;
use Illuminate\Support\Collection;

interface ModelRepository
{
    /**
     * Include soft deleted models in operations that normally exclude them.
     *
     * @return \App\Repositories\Contracts\ModelRepository
     */
    public function includeSoftDeleted(): ModelRepository;

    /**
     * Save a new model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function create(Model $model): bool;

    /**
     * Update an existing model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function update(Model $model): bool;

    /**
     * Soft delete a model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function delete(Model $model): bool;

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

    /**
     * Retieve a single model by its primary key.
     *
     * @param  mixed $id
     * @return \App\Models\Model|null
     */
    public function find($id): ?Model;

    /**
     * Retrieve a single model by a given unique field.
     *
     * @param string $field
     * @param mixed  $value
     * @return \App\Models\Model|null
     */
    public function findBy($field, $value): ?Model;

    /**
     * Retrieve all models.
     *
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(): Collection;

    /**
     * Count the number of models.
     *
     * @return int
     */
    public function count(): int;
}
