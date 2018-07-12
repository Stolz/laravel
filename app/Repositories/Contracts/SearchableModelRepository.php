<?php

namespace App\Repositories\Contracts;

interface SearchableModelRepository extends ModelRepository
{
    /**
     * Retrieve all models matching a search criteria.
     *
     * @param  array $criteria
     * @return \Illuminate\Support\Collection of \App\Models\Model
     */
    public function search(array $criteria): \Illuminate\Support\Collection;

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
    public function paginateSearch(array $criteria, int $perPage = 15, int $page = 1, string $sortBy = null, string $sortDirection = 'asc'): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
