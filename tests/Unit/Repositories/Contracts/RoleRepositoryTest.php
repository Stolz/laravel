<?php

namespace Tests\Unit\Repositories\Contracts;

use App\Models\Permission;
use App\Models\Role;
use App\Traits\AttachesRepositories;
use Tests\Traits\RefreshDatabase;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories;

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
        $this->roleRepository->create($this->role);

        // Create test permissions
        $this->permissions = collect([
            Permission::make(['name' => 'first']),
            Permission::make(['name' => 'last'])
        ]);
        $this->permissionRepository->create($this->permissions->first());
        $this->permissionRepository->create($this->permissions->last());
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
        $this->assertFalse($this->roleRepository->hasPermission($this->role, 'first'));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ['first', 'last']));

        // Asign permissions to role
        $this->roleRepository->replacePermissions($this->role, $this->permissions);

        // Role shuld have now both permissions
        $this->assertTrue($this->roleRepository->hasPermission($this->role, 'first'));
        $this->assertTrue($this->roleRepository->hasPermission($this->role, 'last'));
        $this->assertTrue($this->roleRepository->hasPermission($this->role, ['first', 'last']));

        // But no other permissions
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ['first', 'test']));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, ['last', 'test']));
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
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, 'first'));
        $this->assertFalse($this->roleRepository->hasAnyPermission($this->role, ['first', 'last']));

        // Asign permissions to role
        $this->roleRepository->replacePermissions($this->role, $this->permissions);

        // Role shuld have now both permissions
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, 'first'));
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, 'last'));
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, ['first', 'last']));

        // Even with other permissions
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, ['first', 'test']));
        $this->assertTrue($this->roleRepository->hasAnyPermission($this->role, ['last', 'test']));

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
        $this->assertTrue($this->roleRepository->hasPermission($this->role, ['first', 'last']));
    }

    /**
     * Test add permission to role.
     *
     * @return void
     */
    public function testAddPermission()
    {
        $this->assertFalse($this->roleRepository->hasPermission($this->role, 'first'));
        $this->assertTrue($this->roleRepository->addPermission($this->role, $this->permissions->first()));
        $this->assertTrue($this->roleRepository->hasPermission($this->role, 'first'));
    }


    /**
     * Test remove permission to role.
     *
     * @return void
     */
    public function testRemovePermission()
    {
        $this->roleRepository->addPermission($this->role, $this->permissions->first());
        $this->assertTrue($this->roleRepository->hasPermission($this->role, 'first'));
        $this->assertTrue($this->roleRepository->removePermission($this->role, $this->permissions->first()));
        $this->assertFalse($this->roleRepository->hasPermission($this->role, 'first'));
    }
}
