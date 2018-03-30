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

if (! function_exists('json')) {
    /**
     * Returns the JSON representation of a value.
     *
     * If current environment is not production force JSON_PRETTY_PRINT option.
     *
     * @param mixed $value
     * @param int $options
     * @param int $depth
     * @return string
     */
    function json($value, int $options = 0, int $depth = 512): string
    {
        // Whether or not JSON should have a human friendly format
        static $prettyJson;

        // For performance reasons 'production' environment does not print pretty JSON
        if ($prettyJson === null)
            $prettyJson = ! app()->environment('production');

        if ($prettyJson)
            $options = $options | JSON_PRETTY_PRINT;

        return json_encode($value, $options, $depth);
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