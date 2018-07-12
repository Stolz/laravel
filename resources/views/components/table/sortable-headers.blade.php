<?php
$routeParameters = (isset($parameters)) ? $parameters : [];
$buildLinks = function ($labels, $routeParameters = []): array {
    $route = Route::current()->getName();
    $column = request('sortBy');
    $direction = request('sortDir');
    $links = [];

    foreach ($labels as $key => $label) {
        $routeParameters['sortBy'] = $key;

        $class = null;
        if ($key === $column) {
            $class = 'active';

            if ($direction !== 'desc') {
                $routeParameters['sortDir'] = 'desc';
                $class .= ' desc';
            } else {
                $class .= ' asc';
            }
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
