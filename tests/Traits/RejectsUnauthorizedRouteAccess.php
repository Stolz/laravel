<?php

namespace Tests\Traits;

trait RejectsUnauthorizedRouteAccess
{
    /**
     * Tests route is not a accessible for guest users or users without permissions.
     *
     * @param  string $route
     * @param  string $method
     * @param  string|null $guard
     * @return self
     */
    protected function assertRejectsUnauthorizedAccessToRoute(string $route, string $method, string $guard = null)
    {
        // Test unauthenticated user is redirected to login page
        $this->assertGuest($guard);
        $response = $this->{$method}($route);
        if ($guard === 'api') {
            $response->assertUnauthorized();
        } else {
            $response->assertRedirect(route('login'));
        }

        // Test authenticated user without permissions is dennied access
        $response = $this->actingAs($this->user, $guard)->{$method}($route);
        $response->assertForbidden();

        // Make sure we don't leave traces for comming tests
        $this->app['auth']->guard($guard)->logout();
        $this->assertGuest($guard);

        return $this;
    }
}
