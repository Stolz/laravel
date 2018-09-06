<?php

namespace Tests\Feature;

use App\Traits\AttachesRepositories;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class BasicTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, RejectsUnauthorizedRouteAccess;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create user with no permissions
        $this->user = $this->createUser();

        // Create user with all permissions
        $this->admin = $this->createUser([], ['name' => 'Admin']);
    }

    /**
     * Test all routes can be parsed.
     *
     * @return void
     */
    public function testRoutes()
    {
        $this->artisan('route:list');
        $output = app('Illuminate\Contracts\Console\Kernel')->output();
        $this->assertRegExp('/Domain.*Method.*URI.*Name.*Action.*Middleware/', $output);
    }

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
        $response->assertSee(e($user->getName()));
    }

    /**
     * Tests access module home page is accessible.
     *
     * @return void
     */
    public function testAccessModuleHomePage()
    {
        // User without permissions
        $route = route('access.home');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Access module');
    }

    /**
     * Tests master module home page is accessible.
     *
     * @return void
     */
    public function testMasterModuleHomePage()
    {
        // User without permissions
        $route = route('master.home');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Master module');
    }
}
