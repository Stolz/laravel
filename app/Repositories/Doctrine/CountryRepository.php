<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\CountryRepository as CountryRepositoryContract;

class CountryRepository extends ModelRepository implements CountryRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = \App\Models\Country::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'country';

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
}
