<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class RoleBasedPolicyTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /**
     * Test policiy permission checks.
     *
     * @return void
     */
    public function testPolicyPermissions()
    {
        $this->artisan('db:seed', ['--class' => 'PermissionsSeeder']);

        // User of super admin role always has permission
        $admin = $this->createUser([], ['name' => 'Admin']);
        $this->assertTrue($admin->can('create', User::class));

        // User of normal role does not have permission ...
        $user = $this->createUser();
        $role = $user->getRole();
        $this->assertFalse($user->can('create', User::class));
        $this->assertFalse($user->can('list', User::class));

        // ... even if it has module permission ...
        $this->roleRepository->addPermission($role, $this->permissionRepository->findBy('name', 'use-access-module'));
        $this->assertTrue($user->can('access', 'module'));
        $this->assertFalse($user->can('master', 'module'));
        $this->assertFalse($user->can('create', User::class));

        // ... until final permission is added
        $this->roleRepository->addPermission($role, $this->permissionRepository->findBy('name', 'user-create'));
        $this->assertTrue($user->can('create', User::class));
        $this->assertFalse($user->can('list', User::class));
    }
}