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
    public function setUp(): void
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
        $this->artisan('route:list')->assertExitCode(0);

        // Workaround for BUG in Laravel 5.7. The assertExitCode(0) does not trigger
        // any assertion and PHP Unit flags this tests as risky
        $this->assertTrue(true);
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
}
