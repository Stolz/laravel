<?php

namespace App\Traits;

trait AttachesRepositories
{
    /**
     * Whetherther the repositories have been attached.
     *
     * @var bool
     */
    protected $repositoriesAttached = false;

    /**
     * Attach to the current class an instance of every repository contract.
     *
     * @return void
     */
    protected function attachRepositories()
    {
        if ($this->repositoriesAttached) {
            return;
        }

        $this->repositoriesAttached = true;
        $contracts = \App\Providers\RepositoryServiceProvider::REPOSITORIES;

        foreach ($contracts as $contract) {
            $repository = lcfirst(str_replace('\\', '', str_replace_first('App\Repositories\Contracts\\', '', $contract)));
            $this->{$repository} = app($contract);
        }
    }
}
