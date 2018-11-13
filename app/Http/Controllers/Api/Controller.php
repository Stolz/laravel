<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Contracts\Pagination\Paginator;

abstract class Controller extends BaseController
{
    /**
     * Return a JSON response obeying \JsonSerializable contract.
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

        // All our models implement \JsonSerializable contract to prevent including hidden fields in JSON responses.
        // Laravel automatically applies the jsonSerialize() method of the contract whenever a controller returns an
        // instance of either Model or Collection. However, when an instance of Paginator is returned, Laravel does
        // not automatically apply the method to the underlying collection. To prevent leaking hidden model fields
        // we need to apply it manually for such case
        if ($data instanceof Paginator)
            $data->setCollection($data->getCollection()->map->jsonSerialize());

        // Force JSON_PRETTY_PRINT option when environment is not production.
        if ($prettyJson === null)
            $prettyJson = ! app()->environment('production');

        if ($prettyJson)
            $options = $options | JSON_PRETTY_PRINT;

        return response()->json($data, $status, $headers, $options);
    }
}
