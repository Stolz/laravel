<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class AccessModuleTest extends TestCase
{
    use RefreshDatabase, CreatesUsers, RejectsUnauthorizedRouteAccess;

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
     * Tests home page of the module.
     *
     * @return void
     */
    public function testHomePage()
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
     * Tests users list page.
     *
     * @return void
     */
    public function testUserIndex()
    {
        // User without permissions
        $route = route('access.user.index');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Back to module');
    }

    /**
     * Tests create user page.
     *
     * @return void
     */
    public function testUserCreateForm()
    {
        // User without permissions
        $route = route('access.user.create');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests create user action.
     *
     * @return void
     */
    public function testUserCreate()
    {
        // User without permissions
        $route = route('access.user.store');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'post');

        // User with permissions. Incomplete data
        $referer = route('access.user.create');
        $response = $this->actingAs($this->admin)->from($referer)->post($route);
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
     * Tests show user page.
     *
     * @return void
     */
    public function testUserShow()
    {
        // User without permissions
        $route = route('access.user.show', [$this->user->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Return');
    }

    /**
     * Tests update user page.
     *
     * @return void
     */
    public function testUserUpdateForm()
    {
        // User without permissions
        $route = route('access.user.edit', [$this->user->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests update user action.
     *
     * @return void
     */
    public function testUserUpdate()
    {
        // User without permissions
        $route = route('access.user.update', [$id = $this->user->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'put');

        // User with permissions. Incomplete data
        $referer = route('access.user.edit', [$id]);
        $response = $this->actingAs($this->admin)->from($referer)->put($route);
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
     * Tests delete user action.
     *
     * @return void
     */
    public function testUserDelete()
    {
        // User without permissions
        $route = route('access.user.destroy', [$this->user->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'delete');

        // User with permissions
        $referer = route('access.user.index');
        $response = $this->actingAs($this->admin)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests roles list page.
     *
     * @return void
     */
    public function testRoleIndex()
    {
        // User without permissions
        $route = route('access.role.index');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Back to module');
    }

    /**
     * Tests create role page.
     *
     * @return void
     */
    public function testRoleCreateForm()
    {
        // User without permissions
        $route = route('access.role.create');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests create role action.
     *
     * @return void
     */
    public function testRoleCreate()
    {
        // User without permissions
        $route = route('access.role.store');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'post');

        // User with permissions. Incomplete data
        $referer = route('access.role.create');
        $response = $this->actingAs($this->admin)->from($referer)->post($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\Role::class)->raw([
            'permissions' => ['use-access-module'],
        ]);

        $response = $this->post($route, $data);
        $response->assertRedirect(route('access.role.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests show role page.
     *
     * @return void
     */
    public function testRoleShow()
    {
        // User without permissions
        $route = route('access.role.show', [$this->user->getRole()->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Return');
    }

    /**
     * Tests update role page.
     *
     * @return void
     */
    public function testRoleUpdateForm()
    {
        // User without permissions
        $route = route('access.role.edit', [$this->user->getRole()->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests update role action.
     *
     * @return void
     */
    public function testRoleUpdate()
    {
        // User without permissions
        $route = route('access.role.update', [$id = $this->user->getRole()->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'put');

        // User with permissions. Incomplete data
        $referer = route('access.role.edit', [$id]);
        $response = $this->actingAs($this->admin)->from($referer)->put($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\Role::class)->raw([
            'permissions' => ['use-access-module'],
        ]);

        $response = $this->put($route, $data);
        $response->assertRedirect(route('access.role.show', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests delete role action.
     *
     * @return void
     */
    public function testRoleDelete()
    {
        $role = factory(\App\Models\Role::class)->make();
        $this->roleRepository->create($role);

        // User without permissions
        $route = route('access.role.destroy', [$role->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'delete');

        // User with permissions
        $referer = route('access.role.index');
        $response = $this->actingAs($this->admin)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
