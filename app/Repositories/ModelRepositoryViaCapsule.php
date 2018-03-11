<?php

namespace App\Repositories;

use App\Models\Model;
use App\Repositories\Contracts\ModelRepository;

abstract class ModelRepositoryViaCapsule implements ModelRepository
{
    /**
     * Instance of the service used to interact with database.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $db;

    /**
     * Database table to use.
     *
     * @var string
     */
    protected $table;

    /**
     * Class constructor.
     *
     * Inject dependencies.
     *
     * @param \Illuminate\Database\ConnectionResolverInterface $database
     * @return void
     */
    public function __construct(\Illuminate\Database\ConnectionResolverInterface $database)
    {
        $this->db = $database;
    }

    /**
     * Begin a new query on the model table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query(): \Illuminate\Database\Query\Builder
    {
        return $this->db->table($this->table);
    }

    /**
     * Convert database record into domain model.
     *
     * @param  \StdClass $record
     * @return \App\Models\Model
     */
    abstract protected function recordToModel(\StdClass $record);

    /**
     * Convert domain model into database record.
     *
     * @param \App\Models\Model $model
     * @return array
     */
    protected function modelToRecord(Model $model): array
    {
        return $model->toArray();
    }

    // Contract ====================================================================

    /**
     * Save a new model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function create(Model $model): bool
    {
        $columns = array_except($this->modelToRecord($model), ['id', 'created_at']);

        // Set create time
        if ($createdAt = method_exists($model, 'setCreatedAt'))
            $columns['created_at'] = $createdAt = now();

        // Insert into database
        if (! $id = $this->query()->insertGetId($columns))
            return false;

        // Apply changes to model
        if ($createdAt)
            $model->setCreatedAt($createdAt);
        if (method_exists($model, 'setId'))
            $model->setId($id);

        return true;
    }

    /**
     * Update an existing model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function update(Model $model): bool
    {
        $columns = array_except($this->modelToRecord($model), ['id', 'updated_at']);

        // Set update time
        if ($updatedAt = method_exists($model, 'setUpdatedAt'))
            $columns['updated_at'] = $updatedAt = now();

        // Update record in database
        if (! $updated = $this->query()->whereId($model->getId())->limit(1)->update($columns))
            return false;

        // Apply changes to model
        if ($updatedAt)
            $model->setUpdatedAt($updatedAt);

        return true;
    }

    /**
     * Delete a model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return (bool) $this->query()->whereId($model->getId())->limit(1)->delete();
    }

    /**
     * Retieve a single model by its primary key.
     *
     * @param  mixed $id
     * @return \App\Models\Model|null
     */
    public function find($id): ?Model
    {
        return $this->findBy('id', $id);
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
        $found = $this->query()->where($field, $value)->first();

        return ($found) ? $this->recordToModel($found) : null;
    }

    /**
     * Retrieve all models.
     *
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(): \Illuminate\Support\Collection
    {
        return $this->query()->get()->transform(function ($record) {
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
        return $this->query()->count();
    }
}
