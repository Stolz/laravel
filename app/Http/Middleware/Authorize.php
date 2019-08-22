<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authorize as Middleware;

class Authorize extends Middleware
{
    /**
     * Checks if the given string looks like a fully qualified class name.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isClassName($value)
    {
        // Class App\Providers\AuthServiceProvider defines a 'module' policy
        // alias which the parent method misinterprets as a route model
        // instead of a class. This work around fixes it.
        if ($value === 'module') {
            return true;
        }

        return parent::isClassName($value);
    }
}
