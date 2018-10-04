<?php
$routeParameters = (isset($parameters)) ? $parameters : [];

if (request()->has('search'))
    $routeParameters['search'] = request('search');

$buildLinks = function ($labels, $routeParameters = []): array {
    $route = Route::current()->getName();
    $column = request('sortBy');
    $direction = request('sortDir', 'asc');
    $links = [];

    foreach ($labels as $key => $label) {
        // If key starts with underscore it means it is a non sortable header
        if ($key[0] === '_') {
            $links[$key] = '<span class="disabled">' . e($label) . '</span>';
            continue;
        }
        $routeParameters['sortBy'] = $key;

        if ($key === $column) {
            $class = 'active';

            if ($direction !== 'desc') {
                $routeParameters['sortDir'] = 'desc';
                $class .= ' desc';
            } else {
                $routeParameters['sortDir'] = 'asc';
                $class .= ' asc';
            }
        } else {
            $routeParameters['sortDir'] = $direction;
            $class = null;
        }

        $url = route($route, $routeParameters);
        $links[$key] = sprintf('<a href="%s" class="%s">%s</a>', $url, $class, e($label));
    }

    return $links;
};
$headers = $buildLinks($headers, $routeParameters);
?>

@foreach ($headers as $header)
    <th class="sortable">{!! $header !!}</th>
@endforeach
