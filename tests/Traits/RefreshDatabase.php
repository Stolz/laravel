<?php

namespace Tests\Traits;

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
            $connection->rollBack();
        });
    }
}
