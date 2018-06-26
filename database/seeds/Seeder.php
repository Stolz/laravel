<?php

use App\Traits\AttachesRepositories;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{
    use AttachesRepositories;

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->attachRepositories();
    }
}
