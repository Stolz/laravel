<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Instance of the service used to interact with permissions.
     *
     * @var \App\Repositories\Contracts\PermissionRepository
     */
    protected $permissionRepository;

    /**
     * Instance of the service used to interact with roles.
     *
     * @var \App\Repositories\Contracts\RoleRepository
     */
    protected $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\PermissionRepository $permissionRepository
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\PermissionRepository $permissionRepository, \App\Repositories\Contracts\RoleRepository $roleRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a list of roles.
     *
     * @param  \App\Http\Requests\Role\Index $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(\App\Http\Requests\Role\Index $request): JsonResponse
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'name');

        // Get roles from repository
        $roles = $this->roleRepository->paginate($perPage, $page, $sortBy, $sortDirection);

        return $this->json($roles);
    }

    /**
     * Display the specified role.
     *
     * @param  \App\Http\Requests\Role\View $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(\App\Http\Requests\Role\View $request, Role $role): JsonResponse
    {
        // Include permissions in response
        $response = $role->jsonSerialize();
        $response['permissions'] = $role->getPermissionsNames();

        return $this->json($response);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \App\Http\Requests\Role\Create $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\App\Http\Requests\Role\Create $request): JsonResponse
    {
        // Get request input
        $attributes = $request->only('name', 'description');
        $permissions = $this->permissionRepository->getBy('name', $request->permissions);

        // Create a role with the provided input
        $role = Role::make($attributes);

        // Attempt to save role into the repository
        $created = $this->roleRepository->create($role) and $this->roleRepository->replacePermissions($role, $permissions);

        // Success
        if ($created)
            return $this->json(['created' => true, 'id' => $role->getId()], 201);

        // Something went wrong
        return $this->json(['created' => false], 500);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \App\Http\Requests\Role\Update $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\App\Http\Requests\Role\Update $request, Role $role): JsonResponse
    {
        // Get request input
        $attributes = $request->only('name', 'description');
        $permissions = $this->permissionRepository->getBy('name', $request->permissions);

        // Apply changes to the role
        $role->set($attributes);

        // Attempt to update role
        $updated = $this->roleRepository->update($role);
        $permissionsUpdated = $this->roleRepository->replacePermissions($role, $permissions);

        // Success
        if ($updated and $permissionsUpdated)
            return $this->json(['updated' => true]);

        // Something went wrong
        return $this->json(['updated' => false], 500);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  \App\Http\Requests\Role\Delete $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(\App\Http\Requests\Role\Delete $request, Role $role): JsonResponse
    {
        // Delete role from repository
        $deleted = $this->roleRepository->delete($role);

        return $this->json(['deleted' => $deleted], $deleted ? 200 : 500);
    }
}
