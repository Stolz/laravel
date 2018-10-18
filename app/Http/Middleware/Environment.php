<?php

namespace App\Http\Middleware;

class Environment
{
    /**
     * Ensure the app is running in one of the environments provided as parameters.
     *
     * Sample route: Route::get('test', 'TestController@testSomething')->middleware('env:local,staging');
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  array ...$environments
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$environments)
    {
        return (app()->environment($environments)) ? $next($request) : abort(404);
    }
}
