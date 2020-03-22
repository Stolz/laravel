<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Role;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesPermissions;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, CreatesPermissions, RejectsUnauthorizedRouteAccess;

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
     * Tests display a list of roles.
     *
     * @return void
     */
    public function testIndex()
    {
        // User without permissions
        $route = route('api.role.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-list']]);
        $this->actingAs($user, 'api');

        // Test non empty list
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount($this->roleRepository->count(), 'data');

        // Create test role
        $role = factory(Role::class)->make();
        $this->roleRepository->create($role);

        // Test sorting results
        $route = route('api.role.index', ['sort_by' => 'id', 'sort_dir' => 'desc']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount($this->roleRepository->count(), 'data');
        $this->assertEquals($role->getId(), $response->json('data.0.id'));
    }

    /**
     * Test display the specified role.
     *
     * @return void
     */
    public function testShow()
    {
        // User without permissions
        $route = route('api.role.show', $this->user->getRole()->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-view']]);
        $this->actingAs($user, 'api');

        // Test existing role
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($this->user->getRole()->jsonSerialize());
        $response->assertJsonStructure(['permissions']);

        // Test non existing role
        $route = route('api.role.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();
    }

    /**
     * Tests store a newly created role in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('api.role.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-create']]);
        $this->actingAs($user, 'api');

        // Test with incomplete data
        $response = $this->post($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test with complete data
        $permission = $this->createPermission();
        $data = factory(Role::class)->raw([
            'permissions' => [$permission->getName()],
        ]);
        $response = $this->post($route, $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'created']);
        $response->assertJson(['created' => true]);
    }

    /**
     * Tests update the specified role in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // User without permissions
        $route = route('api.role.update', $this->user->getRole()->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-update']]);
        $this->actingAs($user, 'api');

        // Test existing role with incomplete data
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing role with complete data
        $permission = $this->createPermission();
        $data = factory(Role::class)->raw([
            'permissions' => [$permission->getName()],
        ]);
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);

        // Test non existing role
        $route = route('api.role.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();
    }

    /**
     * Tests remove the specified role from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Create test role
        $role = factory(Role::class)->make();
        $this->roleRepository->create($role);

        // User without permissions
        $route = route('api.role.destroy', $role->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-delete']]);
        $this->actingAs($user, 'api');

        // Test existing role
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);

        // Test non existing role
        $route = route('api.role.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();
    }
}
