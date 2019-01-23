<?php

namespace Tests\Http\Controllers\Api;

use App\Models\User;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers;

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
     * Tests display a list of users.
     *
     * @return void
     */
    public function testIndex()
    {
        // Test non empty list
        $route = route('api.user.index');
        $user = $this->createUser(['name' => 'zzz']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(2, 'data');

        // Test sorting results
        $route = route('api.user.index', ['sort_by' => 'name', 'sort_dir' => 'desc']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(2, 'data');
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
        // Test non existing user
        $route = route('api.user.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();

        // Test existing user
        $user = $this->createUser();
        $route = route('api.user.show', $user->getId());
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($user->jsonSerialize());
    }

    /**
     * Tests store a newly created user in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // Test with incomplete data
        $route = route('api.user.store', ['random']);
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
        // Test non existing user
        $route = route('api.user.update', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();

        // Test existing user with incomplete data
        $user = $this->createUser();
        $route = route('api.user.update', $user->getId());
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing user with complete data
        $data = factory(User::class)->raw(['role' => $this->createUser()->getRole()->getId()]);
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);
    }

    /**
     * Tests remove the specified user from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Test non existing user
        $route = route('api.user.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();

        // Test existing user
        $user = $this->createUser();
        $route = route('api.user.destroy', $user->getId());
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);
    }
}
