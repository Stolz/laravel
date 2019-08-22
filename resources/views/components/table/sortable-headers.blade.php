<?php
$routeParameters = (isset($parameters)) ? $parameters : [];

if (request()->has('search')) {
    $routeParameters['search'] = request('search');
}

$buildLinks = function ($labels, $routeParameters = []): array {
    $route = Route::current()->getName();
    $column = request('sort_by');
    $direction = request('sort_dir', 'asc');
    $links = [];

    foreach ($labels as $key => $label) {
        // If key starts with underscore it means it is a non sortable header
        if ($key[0] === '_') {
            $links[$key] = '<span class="disabled">' . e($label) . '</span>';
            continue;
        }
        $routeParameters['sort_by'] = $key;

        if ($key === $column) {
            $class = 'active';

            if ($direction !== 'desc') {
                $routeParameters['sort_dir'] = 'desc';
                $class .= ' desc';
            } else {
                $routeParameters['sort_dir'] = 'asc';
                $class .= ' asc';
            }
        } else {
            $routeParameters['sort_dir'] = $direction;
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
