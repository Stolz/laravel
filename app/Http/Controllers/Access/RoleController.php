<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\Role;

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
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\Role\Index $request)
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'name');

        // Get roles from repository
        $roles = $this->roleRepository->paginate($perPage, $page, $sortBy, $sortDirection);

        // Load view
        return view('modules.access.role.index')->withRoles($roles);
    }

    /**
     * Display the specified role.
     *
     * @param  \App\Http\Requests\Role\View $request
     * @param  \App\Models\Role  $role
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Http\Requests\Role\View $request, Role $role, \App\Repositories\Contracts\UserRepository $userRepository)
    {
        // Load view
        return view('modules.access.role.show')->with([
            'role' => $role,
            'users' => $userRepository->getBy('role', $role),
        ]);
    }

    /**
     * Show the form for creating a new role.
     *
     * @param  \App\Http\Requests\Role\PreCreate $request
     * @return \Illuminate\Http\Response
     */
    public function create(\App\Http\Requests\Role\PreCreate $request)
    {
        // Load view
        return view('modules.access.role.create')->with([
            'permissionsTree' => \PermissionsSeeder::tree(),
            'role' => Role::make(),
            'selectedPermissions' => collect(),
        ]);
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  \App\Http\Requests\Role\PreUpdate $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Http\Requests\Role\PreUpdate $request, Role $role)
    {
        // Load view
        return view('modules.access.role.update')->with([
            'permissionsTree' => \PermissionsSeeder::tree(),
            'role' => $role,
            'selectedPermissions' => $role->getPermissionsNames(),
        ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \App\Http\Requests\Role\Create $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Role\Create $request)
    {
        // Get request input
        $attributes = $request->only('name', 'description');
        $permissions = $this->permissionRepository->getBy('name', array_keys($request->permissions));

        // Create a role with the provided input
        $role = Role::make($attributes);

        // Attempt to save role into the repository
        $created = $this->roleRepository->create($role) and $this->roleRepository->replacePermissions($role, $permissions);

        // Success
        if ($created) {
            return redirect()->route('access.role.index')->with('success', sprintf(_("Role '%s' successfully created"), $role));
        }

        // Something went wrong
        return redirect()->back()->withInput()->with('error', sprintf(_("Unable to create role '%s'"), $role));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \App\Http\Requests\Role\Update $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Role\Update $request, Role $role)
    {
        // Get request input
        $attributes = $request->only('name', 'description');
        $permissions = $this->permissionRepository->getBy('name', array_keys($request->permissions));

        // Apply changes to the role
        $role->set($attributes);

        // Attempt to update role
        $updated = $this->roleRepository->update($role);
        $permissionsUpdated = $this->roleRepository->replacePermissions($role, $permissions);

        // Success
        if ($updated and $permissionsUpdated) {
            return redirect()->route('access.role.show', $role->getId())->with('success', sprintf(_("Role '%s' successfully updated"), $role));
        }

        // Something went wrong
        return redirect()->back()->withInput()->with('error', sprintf(_("Unable to update role '%s'"), $role));
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  \App\Http\Requests\Role\Delete $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Http\Requests\Role\Delete $request, Role $role)
    {
        // Attempt to delete role
        $deleted = $this->roleRepository->delete($role);

        // Something went wrong
        if (! $deleted) {
            return redirect()->back()->with('error', sprintf(_("Unable to delete role '%s'"), $role));
        }

        // Success
        $redirectBack = ($request->input('_from') === 'access.role.show') ? redirect()->route('access.role.index') : redirect()->back();

        return $redirectBack->with('success', sprintf(_("Role '%s' successfully deleted"), $role));
    }
}
