<?php

namespace Doctrine\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Event\Listeners\OracleSessionInit;

class OracleDateFormat extends OracleSessionInit implements EventSubscriber
{
    /**
     * Set Oracle date format to match PHP date format.
     *
     * @param \Doctrine\DBAL\Event\ConnectionEventArgs $args
     * @return void
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        // This subscriber is always loaded by config/doctrine.php but it only makes
        // sense when the database driver is Oracle. Since multiple databases may be used
        // at the same time (for instance, unit tests use SqlLite) we
        // need to ensure the current database driver is Oracle

        $driver = $args->getDriver();

        if ($driver instanceof \Doctrine\DBAL\Driver\AbstractOracleDriver) {
            parent::postConnect($args);
        }
    }
}
