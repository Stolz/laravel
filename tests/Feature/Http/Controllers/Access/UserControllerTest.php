<?php

namespace Tests\Feature\Http\Controller\Access;

use App\Traits\AttachesRepositories;
use Tests\TestCase;
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
        $route = route('access.user.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-list']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON index');
    }

    /**
     * Test display the specified user.
     *
     * @return void
     */
    public function testShow()
    {
        // User without permissions
        $route = route('access.user.show', [$this->user->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-view']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON show');
    }

    /**
     * Test show the form for creating a new user.
     *
     * @return void
     */
    public function testCreate()
    {
        // User without permissions
        $route = route('access.user.create');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-create']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON create');
    }

    /**
     * Tests show the form for editing the specified user.
     *
     * @return void
     */
    public function testEdit()
    {
        // User without permissions
        $route = route('access.user.edit', [$this->user->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-update']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON update');
    }

    /**
     * Tests store a newly created user in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('access.user.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post');

        // User with permissions. Incomplete data
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-create']]);
        $referer = route('access.user.create');
        $response = $this->actingAs($user)->from($referer)->post($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\User::class)->raw([
            'role' => $this->user['role']['id'],
        ]);

        $response = $this->post($route, $data);
        $response->assertRedirect(route('access.user.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests update the specified user in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // User without permissions
        $route = route('access.user.update', [$id = $this->user->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put');

        // User with permissions. Incomplete data
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-update']]);
        $referer = route('access.user.edit', [$id]);
        $response = $this->actingAs($user)->from($referer)->put($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\User::class)->raw([
            'role' => $this->user['role']['id'],
        ]);
        $response = $this->put($route, $data);
        $response->assertRedirect(route('access.user.show', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests remove the specified user from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // User without permissions
        $route = route('access.user.destroy', [$this->user->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'user-delete']]);
        $referer = route('access.user.index');
        $response = $this->actingAs($user)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
