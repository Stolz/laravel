<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class BasicTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /**
     * Tests home page is accessible.
     *
     * @return void
     */
    public function testHomePage()
    {
        // Guest user
        $this->assertGuest();
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('Home page');
        $response->assertSee('Please log in to continue');

        // Authenticated user
        $user = $this->createUser();
        $response = $this->actingAs($user)->get(route('home'));
        $response->assertOk();
        $response->assertSee('Home page');
        $response->assertSee($user->getName());
    }

    /**
     * Test routes can be parsed.
     *
     * @return void
     */
    public function testRoutes()
    {
        $this->artisan('route:list');
        $output = app('Illuminate\Contracts\Console\Kernel')->output();
        $this->assertRegExp('/Domain.*Method.*URI.*Name.*Action.*Middleware/', $output);
    }
}
