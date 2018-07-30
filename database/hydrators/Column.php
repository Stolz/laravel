<?php

namespace Doctrine\Hydrators;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

class Column extends AbstractHydrator
{
    /**
     * Hydrates a single column result as plain array of scalars.
     *
     * @return array
     */
    public function hydrateAllData()
    {
         return $this->_stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
