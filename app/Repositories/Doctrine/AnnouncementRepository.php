<?php

namespace App\Repositories\Doctrine;

use App\Repositories\Contracts\AnnouncementRepository as AnnouncementRepositoryContract;

class AnnouncementRepository extends ModelRepository implements AnnouncementRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $class = \App\Models\Announcement::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $alias = 'announcement';

    /**
     * Retrieve all models.
     *
     * @param array $orderBy
     * @return \Illuminate\Support\Collection of \App\Models\Announcement
     */
    public function all(array $orderBy = []): \Illuminate\Support\Collection
    {
        // Set a default order when none is provided
        if (! $orderBy)
            $orderBy = ['updatedAt' => 'desc', 'createdAt' => 'desc'];

        return parent::all($orderBy);
    }
}
