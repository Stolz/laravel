<?php

namespace App\Repositories\Doctrine;

use App\Models\Model;
use App\Repositories\Contracts\SoftDeletableModelRepository as SoftDeletableModelRepositoryContract;

abstract class SoftDeletableModelRepository extends ModelRepository implements SoftDeletableModelRepositoryContract
{
    /**
     * Whether or not include soft deleted models in the next query.
     *
     * @var bool
     */
    protected $withSoftDeleted = false;

    /**
     * Create a soft delete aware criteria.
     *
     * @param  array $criteria
     * @return array
     */
    protected function softDeleteAwareCriteria(array $criteria = []): array
    {
        if ($this->withSoftDeleted) {
            $this->withSoftDeleted = false; // Ensure the flag is used only once per query
        } else {
            $criteria['deleted_at'] = null;
        }

        return array_map_key('camel_case', $criteria);
    }

    /**
     * Create a soft delete aware query builder.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function softDeleteAwareQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        $queryBuilder = $this->repository->createQueryBuilder($this->modelAlias);

        if ($this->withSoftDeleted) {
            $this->withSoftDeleted = false; // Ensure the flag is used only once per query
        } else {
            $queryBuilder->andWhere("{$this->modelAlias}.deletedAt is NULL");
        }

        return $queryBuilder;
    }

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
        $model->setDeletedAt(now());

        return $this->update($model, ['deleted_at']);
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
        return $this->update($model->setDeletedAt(null), ['deleted_at']);
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
        $criteria = $this->softDeleteAwareCriteria([$field => $value]);

        return $this->repository->findOneBy($criteria);
    }

    /**
     * Retrieve all models.
     *
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(): \Illuminate\Support\Collection
    {
        $criteria = $this->softDeleteAwareCriteria();

        return collect($this->repository->findBy($criteria));
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
        $this->paginateQueryBuilder = $this->softDeleteAwareQueryBuilder();

        return parent::paginate($perPage, $page, $sortBy, $sortDirection);
    }

    /**
     * Count the number of models.
     *
     * @return int
     */
    public function count(): int
    {
        $criteria = $this->softDeleteAwareCriteria();

        return $this->repository->count($criteria);
    }
}
