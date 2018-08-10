<?php

namespace App\Repositories\Doctrine;

use App\Models\Model;
use App\Repositories\Contracts\ModelRepository as ModelRepositoryContract;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * Full class name of the model this repository is in charge of.
     * Constructor ensures child classes define it.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Alias to be used to reference the model within query builder.
     * Constructor ensures child classes define it.
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

        if (! $this->modelAlias)
            throw new \RuntimeException('Missing repository model alias name');

        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($this->modelClass);
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
     * @param array $orderBy For instance ['createdAt' => 'desc', 'name' => 'asc']
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function all(array $orderBy = []): Collection
    {
        return collect($this->repository->findBy([], $orderBy));
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
        return collect($this->repository->findBy([camel_case($field) => $value]));
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

    /**
     * Retrieve a page of a paginated result of all models.
     *
     * @param  int $perPage
     * @param  int $page
     * @param  string $sortBy
     * @param  string $sortDirection Either 'asc' or 'desc'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, int $page = 1, string $sortBy = null, string $sortDirection = 'asc'): LengthAwarePaginator
    {
        // Use default query builder
        $queryBuilder = $this->getQueryBuilder();

        return $this->paginateQueryBuilder($queryBuilder, $perPage, $page, $sortBy, $sortDirection);
    }

    /**
     * Retrieve the IDs of all models.
     *
     * @return array
     */
    public function getAllIds(): array
    {
        // Use our custom hydrator for maximum efficiency
        return $this->getQueryBuilder()->select("{$this->modelAlias}.id")->getQuery()->getResult('column');
    }

    /**
     * Retrieve all models matching a search criteria.
     *
     * @param  array $criteria
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function search(array $criteria): \Illuminate\Support\Collection
    {
        $queryBuilder = $this->getSearchAwareQueryBuilder($criteria); // This method must be defined in the child class

        return collect($queryBuilder->getQuery()->getResult());
    }

    /**
     * Retrieve a page of a paginated result of models matching a search criteria.
     *
     * @param  array $criteria
     * @param  int $perPage
     * @param  int $page
     * @param  string $sortBy
     * @param  string $sortDirection Either 'asc' or 'desc'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateSearch(array $criteria, int $perPage = 15, int $page = 1, string $sortBy = null, string $sortDirection = 'asc'): LengthAwarePaginator
    {
        // Initialize query builder
        $queryBuilder = $this->getPaginateSearchAwareQueryBuilder($criteria, $sortBy); // This method must be defined in the child class

        // Paginate results
        $paginator = $this->paginateQueryBuilder($queryBuilder, $perPage, $page, $sortBy, $sortDirection);

        // Include search parameters in query string
        $paginator->appends(['search' => $criteria]);

        return $paginator;
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
     * Get the base query builder for querying the repository.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->repository->createQueryBuilder($this->modelAlias);
    }

    /**
     * Determine whether the model has the given field.
     *
     * @param  string $field
     * @return bool
     */
    protected function modelHasField($field): bool
    {
        static $modelReflectionProperties;

        if ($modelReflectionProperties === null)
            $modelReflectionProperties = $this->entityManager->getClassMetadata($this->modelClass)->getReflectionProperties();

        return in_array($field, array_keys($modelReflectionProperties));
    }

    /**
     * Replace unsafe pagination parameters with safe ones.
     *
     * @param  int $perPage
     * @param  int $page
     * @param  string $sortBy
     * @param  string $sortDirection Either 'asc' or 'desc'
     * @return array
     */
    protected function sanitizePaginationParameter(int $perPage, int $page, $sortBy, string $sortDirection): array
    {
        $perPage = max(1, min($perPage, static::MAX_PER_PAGE));
        $page = max(1, $page);
        $sortDirection = (strtolower($sortDirection) === 'desc') ? 'desc' : 'asc';

        if ($sortBy !== null) {
            if (str_contains($sortBy, '.'))
                list($alias, $sortBy) = explode('.', $sortBy, 2);
            else $alias = $this->modelAlias;

            // Normal field. Ensure the model has the provided field
            if ($alias === $this->modelAlias) {
                $sortBy = ($this->modelHasField($sortBy)) ? "{$alias}.{$sortBy}" : null;
            // Filed in nested relation
            } else {
                // Doctrine does not have a way to access the nested relation model for
                // us to check if the field exists. Query will fail if it doesn't.
                $sortBy = "{$alias}.{$sortBy}";
            }
        }

        return [$perPage, $page, $sortBy, $sortDirection];
    }

    /**
     * Retrieve a page of a paginated result of all models using the given query builder.
     *
     * @param  \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param  int $perPage
     * @param  int $page
     * @param  string $sortBy
     * @param  string $sortDirection Either 'asc' or 'desc'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function paginateQueryBuilder(QueryBuilder $queryBuilder, int $perPage, int $page, $sortBy, string $sortDirection): LengthAwarePaginator
    {
        // Sanitize pagination parameters
        $originalSortBy = $sortBy; // Original value without alias prefix
        list($perPage, $page, $sortBy, $sortDirection) = $this->sanitizePaginationParameter($perPage, $page, $sortBy, $sortDirection);

        // Apply sorting parameters
        if ($sortBy !== null)
            $queryBuilder->addOrderBy($sortBy, $sortDirection);

        // Get resutls
        $paginator = \LaravelDoctrine\ORM\Pagination\PaginatorAdapter::fromParams($queryBuilder->getQuery(), $perPage, $page)->make();

        // Include sorting parameters in query string
        if ($sortBy !== null)
            $paginator->appends(['sortBy' => $originalSortBy]);
        if ($sortDirection !== 'asc')
            $paginator->appends(['sortDir' => $sortDirection]);

        return $paginator;
    }
}
