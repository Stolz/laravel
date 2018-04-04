<?php

namespace App\Traits;

trait Timestampable
{
    use CreateTimestampable, UpdateTimestampable;

    /**
     * Get the timestamps as an array.
     *
     * @return array
     */
    protected function getTimestampsAsArray(): array
    {
        return $this->getCreateTimestampAsArray() + $this->getUpdateTimestampAsArray();
    }
}
