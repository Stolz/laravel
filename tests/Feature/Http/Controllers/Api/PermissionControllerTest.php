<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class PermissionControllerTest extends TestCase
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
    }

    /**
     * Tests display a list of permissions.
     *
     * @return void
     */
    public function testIndex()
    {
        // User without permissions
        $route = route('api.permission.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-list']]);
        $this->actingAs($user, 'api');

        // Test non empty list
        $response = $this->get($route);
        $response->assertOk();
    }
}
