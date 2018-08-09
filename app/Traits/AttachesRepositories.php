<?php

namespace App\Traits;

trait AttachesRepositories
{
    /**
     * Attach to the current class an instance of every repository contract.
     *
     * @return void
     */
    protected function attachRepositories()
    {
        $contracts = \App\Providers\RepositoryServiceProvider::REPOSITORIES;

        foreach ($contracts as $contract) {
            $repository = lcfirst(str_replace('\\', '', str_replace_first('App\Repositories\Contracts\\', '', $contract)));
            $this->{$repository} = app($contract);
        }
    }
}
