<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface NotificationRepository extends ModelRepository
{
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
    public function paginateUser(User $user, int $perPage = 15, int $page = 1, string $sortBy = null, string $sortDirection = 'asc'): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Count the number of unread notifications for the given user.
     *
     * @param  \App\Models\User $user
     * @return int
     */
    public function countUnread(User $user): int;
}
