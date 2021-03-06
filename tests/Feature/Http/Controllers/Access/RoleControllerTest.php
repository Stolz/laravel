<?php

namespace Tests\Feature\Http\Controller\Access;

use App\Models\Role;
use App\Traits\AttachesRepositories;
use Tests\TestCase;
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
        $route = route('access.role.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-list']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON index');
    }

    /**
     * Test display the specified role.
     *
     * @return void
     */
    public function testShow()
    {
        // User without permissions
        $route = route('access.role.show', [$this->user->getRole()->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-view']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON show');
    }

    /**
     * Test show the form for creating a new role.
     *
     * @return void
     */
    public function testCreate()
    {
        // User without permissions
        $route = route('access.role.create');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-create']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON create');
    }

    /**
     * Tests show the form for editing the specified role.
     *
     * @return void
     */
    public function testEdit()
    {
        // User without permissions
        $route = route('access.role.edit', [$this->user->getRole()->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-update']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON update');
    }

    /**
     * Tests store a newly created role in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('access.role.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post');

        // User with permissions. Incomplete data
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-create']]);
        $referer = route('access.role.create');
        $response = $this->actingAs($user)->from($referer)->post($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $permission = $this->createPermission();
        $data = factory(Role::class)->raw([
            'permissions' => [$permission->getName()],
        ]);
        $response = $this->post($route, $data);
        $response->assertRedirect(route('access.role.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests update the specified role in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // User without permissions
        $route = route('access.role.update', [$id = $this->user->getRole()->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put');

        // User with permissions. Incomplete data
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-update']]);
        $referer = route('access.role.edit', [$id]);
        $response = $this->actingAs($user)->from($referer)->put($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $permission = $this->createPermission();
        $data = factory(Role::class)->raw([
            'permissions' => [$permission->getName()],
        ]);
        $response = $this->put($route, $data);
        $response->assertRedirect(route('access.role.show', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests remove the specified role from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        $role = factory(Role::class)->make();
        $this->roleRepository->create($role);

        // User without permissions
        $user = $this->createUser(['permissions' => ['use-access-module', 'role-delete']]);
        $route = route('access.role.destroy', [$role->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete');

        // User with permissions
        $referer = route('access.role.index');
        $response = $this->actingAs($user)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
