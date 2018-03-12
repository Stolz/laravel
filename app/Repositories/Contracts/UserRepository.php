<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Auth\UserProvider;

interface UserRepository extends SoftDeletableModelRepository, UserProvider
{

}
