<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Role;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesPermissions;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, CreatesPermissions;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create user with all permissions ...
        $this->user = $this->createUser([], ['name' => 'Admin']);

        // ... and authenticate it
        $this->actingAs($this->user, 'api');
    }

    /**
     * Tests display a list of roles.
     *
     * @return void
     */
    public function testIndex()
    {
        // Test non empty list
        $route = route('api.role.index');
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount($this->roleRepository->count(), 'data');

        // Create test role
        $role = factory(Role::class)->make();
        $this->roleRepository->create($role);

        // Test sorting results
        $route = route('api.role.index', ['sort_by' => 'name', 'sort_dir' => 'desc']);
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
        // Test non existing role
        $route = route('api.role.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();

        // Test existing role
        $route = route('api.role.show', $this->user->getRole()->getId());
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($this->user->getRole()->jsonSerialize());
        $response->assertJsonStructure(['permissions']);
    }

    /**
     * Tests store a newly created role in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // Test with incomplete data
        $route = route('api.role.store', ['random']);
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
        // Test non existing role
        $route = route('api.role.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();

        // Test existing role with incomplete data
        $route = route('api.role.update', $this->user->getRole()->getId());
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
    }

    /**
     * Tests remove the specified role from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Test non existing role
        $route = route('api.role.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();

        // Create test role
        $role = factory(Role::class)->make();
        $this->roleRepository->create($role);

        // Test existing role
        $route = route('api.role.destroy', $role->getId());
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);
    }
}
