<?php

namespace App\Repositories\Doctrine;

use App\Models\Notification;
use App\Models\User;
use App\Repositories\Contracts\NotificationRepository as NotificationRepositoryContract;

class NotificationRepository extends ModelRepository implements NotificationRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $class = Notification::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $alias = 'notification';

    /**
     * Create a query builder that filters by user.
     *
     * @param  \App\Models\User $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function userAwareQueryBuilder(User $user): \Doctrine\ORM\QueryBuilder
    {
        return $this->getQueryBuilder()->andWhere("{$this->alias}.user = :user")->setParameter('user', $user);
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
        $queryBuilder = $this->userAwareQueryBuilder($user);

        return $this->paginateQueryBuilder($queryBuilder, $perPage, $page, $sortBy, $sortDirection);
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

    /**
     * Retrieve the last unread notification for the user.
     *
     * @param  \App\Models\User $user
     * @return \App\Models\Notification|null
     */
    public function getLastUnread(User $user): ?Notification
    {
        return $this->repository->findOneBy(['user' => $user, 'readAt' => null], ['id' => 'desc']);
    }

    /**
     * Delete read notifications older than the given date.
     *
     * @param  \Carbon\Carbon $date
     * @return int
     */
    public function deleteReadOlderThan(\Carbon\Carbon $date): int
    {
        $queryBuilder = $this->getQueryBuilder();
        $isRead = $queryBuilder->expr()->isNotNull("{$this->alias}.readAt");
        $isOld = $queryBuilder->expr()->lte("{$this->alias}.createdAt", ':date');

        return $queryBuilder
        ->andWhere($isRead)
        ->andWhere($isOld)->setParameter('date', $date)
        ->delete()
        ->getQuery()
        ->execute();
    }
}
