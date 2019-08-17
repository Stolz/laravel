<?php

namespace App\Repositories\QueryBuilder;

use App\Models\Model;
use App\Repositories\Contracts\ModelRepository as ModelRepositoryContract;
use Illuminate\Support\Collection;

abstract class ModelRepository implements ModelRepositoryContract
{
    /**
     * Maximum number of items per page when using pagination.
     *
     * @const int
     */
    const MAX_PER_PAGE = 100;

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
     * @throws \RuntimeException
     */
    public function __construct(\Illuminate\Database\ConnectionResolverInterface $database)
    {
        if (! $this->table)
            throw new \RuntimeException('Missing database table name');

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
     * @param array $fields If not empty only these fields will be updated
     * @return bool
     */
    public function update(Model $model, array $fields = []): bool
    {
        // Extract columns to update
        $columns = array_except($this->modelToRecord($model), ['id', 'updated_at']);
        if ($fields)
            $columns = array_only($columns, array_map('snake_case', $fields));

        // Set update time
        if ($updatedAt = method_exists($model, 'setUpdatedAt'))
            $columns['updated_at'] = $updatedAt = now();

        // Update record in database
        if (! $this->query()->whereId($model->getId())->limit(1)->update($columns))
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
     * Retrieve a single model by its primary key.
     *
     * @param  mixed $id
     * @return \App\Models\Model|null
     */
    public function find($id): ?Model
    {
        return ($id === null) ? null : $this->findBy('id', $id);
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
        $found = $this->query()->where(snake_case($field), $value)->first();

        return ($found) ? $this->recordToModel($found) : null;
    }

    /**
     * Retrieve all models.
     *
     * @param array $orderBy For instance ['createdAt' => 'desc', 'name' => 'asc']
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(array $orderBy = []): Collection
    {
        $query = $this->query();

        foreach ($orderBy as $column => $direction)
            $query->orderBy($column, $direction);

        return $query->get()->transform(function ($record) {
            return $this->recordToModel($record);
        });
    }

    /**
     * Retrieve multiple models by the values of a given field.
     *
     * @param string $field
     * @param mixed  $value
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function getBy($field, $value): Collection
    {
        return $this->query()->where($field, $value)->get()->transform(function ($record) {
            return $this->recordToModel($record);
        });
    }

    /**
     * Retrieve a page of a paginated result of all models.
     *
     * @param  int $perPage
     * @param  int $page
     * @param  string $sortBy
     * @param  string $sortDirection Either 'asc' or 'desc'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, int $page = 1, string $sortBy = null, string $sortDirection = 'asc'): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Validate parameters
        $perPage = max(1, min($perPage, static::MAX_PER_PAGE));
        $page = max(1, $page);
        $sortBy = ($sortBy !== null and \Schema::hasColumn($this->table, $sortBy)) ? $sortBy : null;
        $sortDirection = ($sortDirection === 'desc') ? 'desc' : 'asc';

        // Reuse queries defined by child classes
        $query = (isset($this->paginateQuery)) ? $this->paginateQuery : $this->query();

        // Apply sorting parameters
        if ($sortBy !== null)
            $query->orderBy($sortBy, $sortDirection);

        // Fetch results
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $paginator->transform(function ($record) {
            return $this->recordToModel($record);
        });

        // Include sorting parameters in query string
        if ($sortBy !== null)
            $paginator->appends(['sort_by' => $sortBy]);
        if ($sortDirection !== 'asc')
            $paginator->appends(['sort_dir' => $sortDirection]);

        return $paginator;
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
