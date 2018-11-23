<?php

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;

trait RefreshDatabase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /**
     * Begin a database transaction on the testing database.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        // Transactions from Laravel RefreshDatabase trait don't work for Doctrine
        // based repositories. We need to use our own implementation

        $connection = $this->app->make('em')->getConnection();
        $connection->beginTransaction();

        $this->beforeApplicationDestroyed(function () use ($connection) {
            while ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
        });
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('doctrine:schema:create');

        $this->app[Kernel::class]->setArtisan(null);
    }
}
