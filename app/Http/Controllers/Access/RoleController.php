<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Instance of the service used to interact with roles.
     *
     * @var \App\Repositories\Contracts\RoleRepository
     */
    protected $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\RoleRepository $roleRepository)
    {
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
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Create an empty role
        $role = Role::make();

        // Load view
        return view('modules.access.role.create')->withRole($role);
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
        $attrtibutes = $request->only('name', 'description');

        // Create a role with the provided input
        $role = Role::make($attrtibutes);

        // Attempt to save role into the repository
        $created = $this->roleRepository->create($role);

        // Success
        if ($created) {
            session()->flash('success', sprintf("Role '%s' successfully created", $role));

            return redirect()->route('access.role.index');
        }

        // Something went wrong
        session()->flash('error', sprintf("Unable to create role '%s'", $role));

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified role.
     *
     * @param  \App\Http\Requests\Role\View $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Http\Requests\Role\View $request, Role $role)
    {
        // Load view
        return view('modules.access.role.show')->withRole($role);
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  \App\Http\Requests\Role\View $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Http\Requests\Role\View $request, Role $role)
    {
        // Load view
        return view('modules.access.role.update')->withRole($role);
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
        $attrtibutes = $request->only('name', 'description');

        // Apply changes to the role
        $role->set($attrtibutes);

        // Attempt to update role
        $updated = $this->roleRepository->update($role);

        // Success
        if ($updated) {
            session()->flash('success', sprintf("Role '%s' successfully updated", $role));

            return redirect()->route('access.role.show', $role->getId());
        }

        // Something went wrong
        session()->flash('error', sprintf("Unable to update role '%s'", $role));

        return redirect()->back()->withInput();
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

        // Set feedback message
        if ($deleted) {
            session()->flash('success', sprintf("Role '%s' successfully deleted", $role));
        } else {
            session()->flash('error', sprintf("Unable to delete role '%s'", $role));
        }

        // Return to the requesting page
        return redirect()->back();
    }
}
