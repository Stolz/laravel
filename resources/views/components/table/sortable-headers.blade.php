<?php
$routeParamenters = (isset($paramenters)) ? $paramenters : [];
$buildLinks = function ($labels, $routeParamenters = []): array {
    $route = Route::current()->getName();
    $column = request('sortBy');
    $direction = request('sortDir');
    $links = [];

    foreach ($labels as $key => $label) {
        $routeParamenters['sortBy'] = $key;

        $class = null;
        if ($key === $column) {
            $class = 'active';

            if ($direction !== 'desc') {
                $routeParamenters['sortDir'] = 'desc';
                $class .= ' desc';
            } else {
                $class .= ' asc';
            }
        }

        $url = route($route, $routeParamenters);
        $links[$key] = sprintf('<a href="%s" class="%s">%s</a>', $url, $class, e($label));
    }

    return $links;
};
$headers = $buildLinks($headers, $routeParamenters);
?>

@foreach ($headers as $header)
    <th class="sortable">{!! $header !!}</th>
@endforeach
