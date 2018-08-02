<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\UserRepository as UserRepositoryContract;
use App\Repositories\Traits\UserProvider;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends SoftDeletableModelRepository implements UserRepositoryContract
{
    use UserProvider;

    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = \App\Models\User::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'user';

    /**
     * Retrieve all models.
     *
     * @param array $orderBy
     * @return \Illuminate\Support\Collection of \App\Models\User
     */
    public function all(array $orderBy = []): \Illuminate\Support\Collection
    {
        // Set a default order when none is provided
        if (! $orderBy)
            $orderBy = ['name' => 'asc'];

        return parent::all($orderBy);
    }

    /**
     * Get a query builder with a search criteria applied to it.
     *
     * @param  array $criteria
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getSearchAwareQueryBuilder(array $criteria): QueryBuilder
    {
        $queryBuilder = $this->getQueryBuilder();

        // Exact search
        if (! empty($criteria['role'])) {
            $condition = $queryBuilder->expr()->eq("{$this->modelAlias}.role", ':s_role');
            $queryBuilder->andWhere($condition)->setParameter('s_role', $criteria['role']);
        }

        // Fuzzy search
        foreach (['name', 'email'] as $field) {
            if (! empty($criteria[$field])) {
                // Oracle LIKE operator is case sensitive. Convert to lower case to make the search really fuzzy
                $needle = '%' . strtolower($criteria[$field]) . '%';
                $condition = $queryBuilder->expr()->like("LOWER({$this->modelAlias}.$field)", ":s_{$field}");
                $queryBuilder->andWhere($condition)->setParameter("s_{$field}", $needle);
            }
        }

        return $queryBuilder;
    }

    /**
     * Get a query builder with joined related objects required for pagination and a search criteria applied to it.
     *
     * @param  array $criteria
     * @param  string|null $sortBy
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getPaginateSearchAwareQueryBuilder(array $criteria, string $sortBy = null): QueryBuilder
    {
        $queryBuilder = $this->getSearchAwareQueryBuilder($criteria);

        // To be able to sort by relations we need to join them but some may already
        // have been added by the search aware QueryBuilder so we need to check first
        $aliases = $queryBuilder->getAllAliases();

        if ($sortBy === 'role.name' and ! in_array('role', $aliases, true))
            $queryBuilder->innerJoin("{$this->modelAlias}.role", 'role');

        return $queryBuilder;
    }
}
