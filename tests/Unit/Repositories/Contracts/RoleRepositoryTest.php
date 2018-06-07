<?php

namespace Tests\Unit\Repositories\Contracts;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Contracts\RoleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create test role
        $this->role = Role::make(['name' => 'test']);
        $this->roleRepository = app(RoleRepository::class);
        $this->roleRepository->create($this->role);

        // Create test permissions
        $this->permissions = collect([
            Permission::make(['name' => 'foo']),
            Permission::make(['name' => 'bar'])
        ]);
        $this->permissionRepository = app(PermissionRepository::class);
        $this->permissionRepository->create($this->permissions->first());
        $this->permissionRepository->create($this->permissions->last());
    }

    /**
     * Run after each test.
     *
     * @return void
     */
    public function tearDown()
    {
        // Transactions from RefreshDatabase trait don't work for Doctrine based repositories
        $this->permissionRepository->all()->each(function ($permission) {
            $this->permissionRepository->delete($permission);
        });
        $this->roleRepository->all()->each(function ($role) {
            $this->roleRepository->delete($role);
        });

        parent::tearDown();
    }

    /**
     * Test whether the role has all of the given permissions by name.
     *
     * @return void
     */
    public function testHasPermission()
    {
        // Empty role should never have permission for anything
        $this->assertFalse($this->roleRepository->hasPermission($this->role, []));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ''));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, 'foo'));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ['foo', 'bar']));

        // Asign permissions to role
        $this->roleRepository->replacePermissions($this->role, $this->permissions);

        // Role shuld have now both permissions
        $this->assertTrue($this->roleRepository->hasPermission($this->role, 'foo'));
        $this->assertTrue($this->roleRepository->hasPermission($this->role, 'bar'));
        $this->assertTrue($this->roleRepository->hasPermission($this->role, ['foo', 'bar']));

        // But no other permissions
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ['foo', 'test']));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ['bar', 'test']));
    }

    /**
     * Test whether the role has any of the given permissions by name.
     *
     * @return void
     */
    public function testHasAnyPermission()
    {
        // Empty role should never have any permission for anything
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, []));
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, ''));
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, 'foo'));
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, ['foo', 'bar']));

        // Asign permissions to role
        $this->roleRepository->replacePermissions($this->role, $this->permissions);

        // Role shuld have now both permissions
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, 'foo'));
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, 'bar'));
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, ['foo', 'bar']));

        // Even with other permissions
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, ['foo', 'test']));
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, ['bar', 'test']));

        // But no other permissions
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, ['hocus', 'pocus']));
    }

    /**
     * Test replace current role permissions with the given ones.
     *
     * @return void
     */
    public function testReplacePermissions()
    {
        $this->assertTrue($this->roleRepository->replacePermissions($this->role, $this->permissions));
        $this->assertTrue($this->roleRepository->hasPermission($this->role, ['foo', 'bar']));
    }
}
