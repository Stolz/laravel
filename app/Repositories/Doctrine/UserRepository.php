<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\UserRepository as UserRepositoryContract;
use App\Repositories\Traits\UserProvider;

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
     * Get the a query builder with a search criteria applied to it.
     *
     * @param  array $criteria
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getSearchAwareQueryBuilder(array $criteria): \Doctrine\ORM\QueryBuilder
    {
        $queryBuilder = $this->getQueryBuilder();

        // Exact search
        if (! empty($criteria['role']))
            $queryBuilder->andWhere($queryBuilder->expr()->eq("{$this->modelAlias}.role", $criteria['role']));

        // Fuzzy search
        foreach (['name', 'email'] as $field) {
            if (! empty($criteria[$field])) {
                // Oracle LIKE operator is case sensitive. Convert to lower case to make the search really fuzzy
                $needle = '%' . strtolower($criteria[$field]) . '%';
                $queryBuilder->andWhere("LOWER({$this->modelAlias}.$field) LIKE :$field")->setParameter($field, $needle);
            }
        }

        return $queryBuilder;
    }
}
