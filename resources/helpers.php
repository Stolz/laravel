<?php

if (! function_exists('_')) {
    /**
     * Dummy gettext alias.
     *
     * @param  string $message
     * @return string
     */
    function _($message)
    {
        return $message;
    }
}

if (! function_exists('d')) {
    /**
     * Dump the passed variables.
     *
     * @see http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list
     * @param  mixed  ...$args
     * @return void
     */
    function d(...$args)
    {
        foreach ($args as $x) {
            (new \Illuminate\Support\Debug\Dumper)->dump($x);
        }
    }
}

if (! function_exists('convert_to_date')) {
    /**
     * Convert a date value to a \Carbon\Carbon instance.
     *
     * Leave null values intact.
     *
     * @param  mixed $date
     * @param  string $format
     * @return \Carbon\Carbon|null
     */
    function convert_to_date($date, string $format = 'Y-m-d H:i:s'): ?\Carbon\Carbon
    {
        if (empty($date))
            return null;

        if ($date instanceof \Carbon\Carbon)
            return $date;

        return \Carbon\Carbon::createFromFormat($format, $date);
    }
}

if (! function_exists('convert_date_to_string')) {
    /**
     * Convert a \Carbon\Carbon instance to string.
     *
     * Leave null values intact.
     *
     * @param  \Carbon\Carbon|null $date
     * @param  string $format
     * @return string|null
     */
    function convert_date_to_string($date, string $format = 'Y-m-d H:i:s')
    {
        if ($date instanceof \Carbon\Carbon)
            return $date->format($format);

        return $date;
    }
}
