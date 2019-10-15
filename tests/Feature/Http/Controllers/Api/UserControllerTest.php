<?php

namespace Tests\Http\Controllers\Api;

use App\Models\User;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class UserControllerTest extends TestCase
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
     * Tests display a list of users.
     *
     * @return void
     */
    public function testIndex()
    {
        // User without permissions
        $route = route('api.user.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-list']]);
        $this->actingAs($user, 'api');

        // Test non empty list
        $user = $this->createUser(['name' => 'zzz']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount($this->userRepository->count(), 'data');

        // Test sorting results
        $route = route('api.user.index', ['sort_by' => 'name', 'sort_dir' => 'desc']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount($this->userRepository->count(), 'data');
        $this->assertEquals($user->getId(), $response->json('data.0.id'));

        // Test searching results
        $route = route('api.user.index', ['search' => ['email' => $user->getEmail()]]);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($user->getId(), $response->json('data.0.id'));
    }

    /**
     * Test display the specified user.
     *
     * @return void
     */
    public function testShow()
    {
        // User without permissions
        $route = route('api.user.show', $this->user->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-view']]);
        $this->actingAs($user, 'api');

        // Test existing user
        $user = $this->createUser();
        $route = route('api.user.show', $user->getId());
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($user->jsonSerialize());

        // Test non existing user
        $route = route('api.user.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();
    }

    /**
     * Tests store a newly created user in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('api.user.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-create']]);
        $this->actingAs($user, 'api');

        // Test with incomplete data
        $response = $this->post($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test with complete data
        $data = factory(User::class)->raw(['role' => $this->createUser()->getRole()->getId()]);
        $response = $this->post($route, $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'created']);
        $response->assertJson(['created' => true]);
    }

    /**
     * Tests update the specified user in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // User without permissions
        $user = $this->createUser();
        $route = route('api.user.update', $user->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-update']]);
        $this->actingAs($user, 'api');

        // Test existing user with incomplete data
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing user with complete data
        $data = factory(User::class)->raw(['role' => $this->createUser()->getRole()->getId()]);
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);

        // Test non existing user
        $route = route('api.user.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();
    }

    /**
     * Tests remove the specified user from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // User without permissions
        $user = $this->createUser();
        $route = route('api.user.destroy', $user->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-delete']]);
        $this->actingAs($user, 'api');

        // Test existing user
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);

        // Test non existing user
        $route = route('api.user.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();
    }
}
