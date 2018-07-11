<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the pagination options from a request.
     *
     * Set sensible defaults in case the request lacks pagination options.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $perPage
     * @param  string $sortBy
     * @param  string $sortDirection
     * @return array
     */
    protected function getPaginationOptionsFromRequest(Request $request, int $perPage = 15, string $sortBy = null, string $sortDirection = 'asc'): array
    {
        $perPage = (int) $request->input('perPage', $perPage);
        $page = (int) $request->input('page', 1);
        $sortBy = $request->input('sortBy', $sortBy);
        $sortDirection = $request->input('sortDir', $sortDirection);

        return [$perPage, $page, $sortBy, $sortDirection];
    }
}
