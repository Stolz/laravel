<?php

namespace App\Traits;

trait Makeable
{
    // Domain logic ================================================================

    /**
     * Class factory.
     *
     * NOTE This function uses the Variable-length argument lists syntax, introduced in PHP 5.6
     *
     * @see http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list
     * @param  mixed ...$attributes
     * @return self
     */
    public static function make(...$attributes): self
    {
        return new static(...$attributes);
    }
}
