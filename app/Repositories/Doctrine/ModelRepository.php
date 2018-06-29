<?php

namespace App\Repositories\Doctrine;

use App\Models\Model;
use App\Repositories\Contracts\ModelRepository as ModelRepositoryContract;

abstract class ModelRepository implements ModelRepositoryContract
{
    /**
     * Maximum number of items per page when using pagination.
     *
     * @const int
     */
    const MAX_PER_PAGE = 100;

    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Alias to be used to reference the model within query builder.
     * constructor ensures child classes define it.
     *
     * @var string
     */
    protected $modelAlias;

    /**
     * Instance of Doctrine entity manager.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Instance of a generic Doctrine repository.
     *
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * Class constructor.
     *
     * Inject dependencies.
     *
     * @param  \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return void
     * @throws \RuntimeException
     */
    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        if (! $this->modelClass)
            throw new \RuntimeException('Missing repository model class name');

        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($this->modelClass);
    }

    /**
     * Get database table name used by the repository.
     *
     * @return string
     */
    protected function getTable(): string
    {
        return $this->entityManager->getClassMetadata($this->modelClass)->getTableName();
    }

    /**
     * Determine whether the model has the given field.
     *
     * @param  string $field
     * @return bool
     */
    protected function modelHasField($field): bool
    {
        $modelReflectionProperties = $this->entityManager->getClassMetadata($this->modelClass)->getReflectionProperties();

        return in_array($field, array_keys($modelReflectionProperties));
    }

    /**
     * Save a new model.
     *
     * @param \App\Models\Model $model
     * @return bool
     */
    public function create(Model $model): bool
    {
        if (method_exists($model, 'setCreatedAt'))
            $model->setCreatedAt(now());

        $this->entityManager->persist($model);
        $this->entityManager->flush($model);

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
        // TODO restrict update to only $fields
        // $fields = array_map('camel_case', $fields);

        if (method_exists($model, 'setUpdatedAt'))
            $model->setUpdatedAt(now());

        $this->entityManager->flush($model);

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
        $this->entityManager->remove($model);
        $this->entityManager->flush($model);

        return true;
    }

    /**
     * Retrieve a single model by its primary key.
     *
     * @param  mixed $id
     * @return \App\Models\Model|null
     */
    public function find($id): ?Model
    {
        return ($id === null) ? null : $this->repository->find($id);
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
        return $this->repository->findOneBy([camel_case($field) => $value]);
    }

    /**
     * Retrieve all models.
     *
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(): \Illuminate\Support\Collection
    {
        return collect($this->repository->findAll());
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
        $sortBy = ($sortBy !== null and $this->modelHasField($sortBy = camel_case($sortBy))) ? $sortBy : null;
        $sortDirection = ($sortDirection === 'desc') ? 'desc' : 'asc';

        // Reuse query builder defined by child classes
        $queryBuilder = (isset($this->paginateQueryBuilder)) ? $this->paginateQueryBuilder : $this->repository->createQueryBuilder($this->modelAlias);

        // Apply sorting parameters
        if ($sortBy !== null)
            $queryBuilder->addOrderBy("{$this->modelAlias}.$sortBy", strtoupper($sortDirection));

        $paginator = \LaravelDoctrine\ORM\Pagination\PaginatorAdapter::fromParams(
            $queryBuilder->getQuery(),
            $perPage,
            $page
        )->make();

        // Include sorting parameters in query string
        if ($sortBy !== null)
            $paginator->appends(['sortBy' => $sortBy]);
        if ($sortDirection !== 'asc')
            $paginator->appends(['sortDir' => $sortDirection]);

        return $paginator;
    }

    /**
     * Count the number of models.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->repository->count([]);
    }
}
