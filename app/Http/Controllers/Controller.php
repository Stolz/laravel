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
     * Get the search options from a request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function getSearchOptionsFromRequest(Request $request): array
    {
        return array_filter($request->input('search', []));
    }

    /**
     * Get the sorting options from a request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $sortBy
     * @param  string $sortDirection
     * @return array
     */
    protected function getSortingOptionsFromRequest(Request $request, string $sortBy = null, string $sortDirection = 'asc'): array
    {
        $sortBy = $request->input('sort_by', $sortBy);

        if ($direction = $request->input('sort_dir') and in_array($direction, ['asc', 'desc'], true)) {
            $sortDirection = $direction;
        }

        return [$sortBy, $sortDirection];
    }

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
        $perPage = (int) $request->input('per_page', $perPage);
        $page = (int) $request->input('page', 1);

        return array_merge(
            [$perPage, $page],
            $this->getSortingOptionsFromRequest($request, $sortBy, $sortDirection)
        );
    }

    /**
     * Get the search and the pagination options from a request.
     *
     * Set sensible defaults in case the request lacks pagination options.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $perPage
     * @param  string $sortBy
     * @param  string $sortDirection
     * @return array
     */
    protected function getSearchPaginationOptionsFromRequest(Request $request, int $perPage = 15, string $sortBy = null, string $sortDirection = 'asc'): array
    {
        return array_merge(
            [$this->getSearchOptionsFromRequest($request)],
            $this->getPaginationOptionsFromRequest($request, $perPage, $sortBy, $sortDirection)
        );
    }

    /**
     * Create a event stream response.
     *
     * The browser will keep the connection open and the $loop will run indefinitely.
     * The output of the $loop will be sent to the browser in real time, without buffering.
     *
     * @see    https://en.wikipedia.org/wiki/Server-sent_events
     * @param  callable $loop
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function eventStream(callable $loop)
    {
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse($loop);
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no'); // Required for NGINX

        return $response;
    }
}
