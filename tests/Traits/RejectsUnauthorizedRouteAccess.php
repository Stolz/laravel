<?php

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;

trait RejectsUnauthorizedRouteAccess
{
    /**
     * Tests route is not a accessible for users without permissions.
     *
     * @param  string $route
     * @param  string $method
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function rejectUnauthorizedRouteAccess(string $route, string $method)
    {
        // Unauthenticated user
        $this->assertGuest();
        $response = $this->{$method}($route);
        $response->assertRedirect(route('login'));

        // User without permissions
        $response = $this->actingAs($this->user)->{$method}($route);
        $response->assertForbidden();

        // Make sure we don't leave traces for comming tests
        $this->app['auth']->logout();
        $this->assertGuest();

        return $response;
    }
}
