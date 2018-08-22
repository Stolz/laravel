<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\CountryRepository as CountryRepositoryContract;

class CountryRepository extends SoftDeletableModelRepository implements CountryRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $class = \App\Models\Country::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $alias = 'country';

    /**
     * Retrieve all models.
     *
     * @param array $orderBy
     * @return \Illuminate\Support\Collection of \App\Models\Country
     */
    public function all(array $orderBy = []): \Illuminate\Support\Collection
    {
        // Set a default order when none is provided
        if (! $orderBy)
            $orderBy = ['name' => 'asc'];

        return parent::all($orderBy);
    }
}
