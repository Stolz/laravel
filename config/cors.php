<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Access-Control-Allow-Credentials header
    |--------------------------------------------------------------------------
    |
    | Whether to expose the response to frontend JavaScript code when the
    | request's credentials mode (Request.credentials) is "include".
    |
    */
    'credentials' => (bool) env('CORS_ALLOW_CREDENTIALS', false),

    /*
    |--------------------------------------------------------------------------
    | Access-Control-Allow-Headers header
    |--------------------------------------------------------------------------
    |
    | Indicate which HTTP headers can be used during the request.
    |
    */
    'headers' => env('CORS_ALLOW_HEADERS', 'Accept,Content-Type,Authorization'),

    /*
    |--------------------------------------------------------------------------
    | Access-Control-Allow-Methods header
    |--------------------------------------------------------------------------
    |
    | Indicate which HTTP headers can be used during the request.
    |
    */
    'methods' => env('CORS_ALLOW_METHODS', 'GET,PATCH,POST,PUT,DELETE,OPTIONS'),

    /*
    |--------------------------------------------------------------------------
    | Access-Control-Allow-Origin header
    |--------------------------------------------------------------------------
    |
    | Valid values are 'null' (literal string, not the nil value), '*' or a URL.
    | For secuity reasons you should only allow access from your frontend app URL.
    | Wildcard subdomains (such *.example.com) are not supported.
    |
    */
    'origin' => env('CORS_ALLOW_ORIGIN', 'null'),
];
