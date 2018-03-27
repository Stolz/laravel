<?php

namespace App\Repositories\Contracts;

use App\Models\Model;

interface ModelRepository
{
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
     * @param array $fields If not empty only these fields will be updated
     * @return bool
     */
    public function update(Model $model, array $fields = []): bool;

    /**
     * Delete a model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function delete(Model $model): bool;

    /**
     * Retrieve a single model by its primary key.
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
    public function all(): \Illuminate\Support\Collection;

    /**
     * Count the number of models.
     *
     * @return int
     */
    public function count(): int;
}
