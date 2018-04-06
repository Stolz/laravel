<?php

namespace App\Repositories\Doctrine;

use App\Models\Model as Notification;
use App\Models\User;
use App\Repositories\Contracts\NotificationRepository as NotificationRepositoryContract;

class NotificationRepository extends ModelRepository implements NotificationRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = \App\Models\Notification::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'n';

    /**
     * Create a query builder that filters by user.
     *
     * @param  \App\Models\User $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function userAwareQueryBuilder(User $user): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository->createQueryBuilder($this->modelAlias)->andWhere("{$this->modelAlias}.user = :user")->setParameter('user', $user);
    }

    /**
     * Retrieve a page of user notifications.
     *
     * @param  \App\Models\User $user
     * @param  int $perPage
     * @param  int $page
     * @param  string $sortBy
     * @param  string $sortDirection Either 'asc' or 'desc'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateUser(User $user, int $perPage = 15, int $page = 1, string $sortBy = null, string $sortDirection = 'asc'): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $this->paginateQueryBuilder = $this->userAwareQueryBuilder($user);

        if ($sortBy === null) {
            $sortBy = 'createdAt';
            $sortDirection = 'desc';
        }

        return parent::paginate($perPage, $page, $sortBy, $sortDirection);
    }

    /**
     * Count the number of unread notifications for the given user.
     *
     * @param  \App\Models\User $user
     * @return int
     */
    public function countUnread(User $user): int
    {
        return $this->repository->count([
            'user' => $user,
            'readAt' => null,
        ]);
    }
}