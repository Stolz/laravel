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

if (! function_exists('array_map_key')) {
    /**
     * Applies a callback to the keys of the given array.
     *
     * @param callable $callback
     * @param array $array
     * @return array
     */
    function array_map_key(callable $callback, array $array)
    {
        $keys = array_map($callback, array_keys($array));

        return array_combine($keys, $array);
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

if (! function_exists('colorize')) {
    /**
     * Return a unique and consistent HEX color code for the given text.
     *
     * You can enforce RGB values (0-255) to fall within a certain range to prevent colors from being too bright or too dark.
     *
     * @param  string $text
     * @param  int $min  Minimum normalized decimal value
     * @param  int $max  Maximum normalized decimal value
     * @return string
     */
    function colorize($text, int $min = 0, $max = 255): string
    {
        static $colorizer;
        if ($colorizer === null)
            $colorizer = new \PHLAK\Colorizer\Colorize();

        return $colorizer->text((string) $text)->normalize($min, $max)->hex();
    }
}

if (! function_exists('previous_index_url')) {
    /**
     * Get the URL of the previous resource listing.
     *
     * @param  string $fallbackUrl
     * @return string
     */
    function previous_index_url(string $fallbackUrl): string
    {
        $previousUrl = url()->previous();
        parse_str(parse_url($previousUrl, PHP_URL_QUERY), $urlParameters);

        if (isset($urlParameters['page']) or isset($urlParameters['search']) or isset($urlParameters['sortBy']))
            return $previousUrl;

        return $fallbackUrl;
    }
}

if (! function_exists('server_sent_event')) {
    /**
     * Send a message in server-sent-events (SSE) format.
     *
     * @see https://html.spec.whatwg.org/multipage/comms.html#server-sent-events
     * @param  array $message
     * @return void
     */
    function server_sent_event(array $message)
    {
        foreach ($message as $key => $value) {
            // Convert message to JSON
            if ($value instanceof \JsonSerializable)
                $value = json_encode($value->jsonSerialize());
            elseif ($value instanceof \Illuminate\Contracts\Support\Arrayable)
                $value = json_encode($value->toArray());
            elseif (! is_null($value) and ! is_scalar($value))
                $value = json_encode($value);

            echo $key, ': ', $value, PHP_EOL;
        }

        echo PHP_EOL;
        ob_flush();
        flush();
    }
}
