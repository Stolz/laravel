<?php

namespace App\Repositories\QueryBuilder;

use App\Models\Model;
use App\Repositories\Contracts\SoftDeletableModelRepository as SoftDeletableModelRepositoryContract;
use Illuminate\Support\Collection;

abstract class SoftDeletableModelRepository extends ModelRepository implements SoftDeletableModelRepositoryContract
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
    public function includeSoftDeleted(): SoftDeletableModelRepositoryContract
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

        if (! $updated)
            $model->setDeletedAt($deletedAt);

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
        $found = $this->softDeleteAwareQuery()->where(snake_case($field), $value)->first();

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
        $query = $this->softDeleteAwareQuery();

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
        return $this->softDeleteAwareQuery()->where($field, $value)->get()->transform(function ($record) {
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
        $this->paginateQuery = $this->softDeleteAwareQuery();

        return parent::paginate($perPage, $page, $sortBy, $sortDirection);
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
