<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Return a JSON response.
     *
     * Force JSON_PRETTY_PRINT option when environment is not production.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0): \Illuminate\Http\JsonResponse
    {
        static $prettyJson;

        if ($prettyJson === null)
            $prettyJson = ! app()->environment('production');

        if ($prettyJson)
            $options = $options | JSON_PRETTY_PRINT;

        return response()->json($data, $status, $headers, $options);
    }
}
