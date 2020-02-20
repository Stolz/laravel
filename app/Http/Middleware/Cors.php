<?php

namespace App\Http\Middleware;

class Cors
{
    /**
     * Add CORS headers to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $config = config('cors');

        $headers = [
            'Access-Control-Allow-Headers' => $config['headers'],
            'Access-Control-Allow-Methods' => $config['methods'],
            'Access-Control-Allow-Origin' => $config['origin'],
        ];

        // Credentials header cannot be used with 'false' value
        if ($config['credentials']) {
            $headers['Access-Control-Allow-Credentials'] = true;
        }

        $response->headers->add($headers);

        return $response;
    }
}
