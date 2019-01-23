<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Permission;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesCountries;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class PermissionControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, CreatesCountries;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create user with all permissions ...
        $this->user = $this->createUser([], ['name' => 'Admin']);

        // ... and authenticate it
        $this->actingAs($this->user, 'api');
    }

    /**
     * Tests display a list of permissions.
     *
     * @return void
     */
    public function testIndex()
    {
        // Test empty list
        $route = route('api.permission.index');
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonCount(0);

        // Create test permissions
        $permissions = ['permission A', 'permission Z'];
        foreach ($permissions as $key => $name) {
            $permissions[$key] = Permission::make(['name' => $name, 'description' => $name]);
            $this->permissionRepository->create($permissions[$key]);
        }

        // Test non empty list
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonCount(count($permissions));

        // Test sorting results
        $route = route('api.permission.index', ['sort_by' => 'description', 'sort_dir' => 'desc']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonCount($count = count($permissions));
        $this->assertEquals($permissions[$count - 1]['id'], $response->json(0)['id']);
    }
}
