<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Instance of the service used to interact with roles.
     *
     * @var \App\Repositories\Contracts\RoleRepository
     */
    protected $roleRepository;

    /**
     * Instance of the service used to interact with users.
     *
     * @var \App\Repositories\Contracts\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\RoleRepository $roleRepository, \App\Repositories\Contracts\UserRepository $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a list of users.
     *
     * @param  \App\Http\Requests\User\Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\User\Index $request)
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'name');

        // Get users from repository
        $users = $this->userRepository->paginate($perPage, $page, $sortBy, $sortDirection);

        // Load view
        return view('modules.access.user.index')->withUsers($users);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Load view
        return view('modules.access.user.create')->with([
            'user' => User::make(),
            'minPasswordLength' => User::MIN_PASSWORD_LENGTH,
            'roles' => $this->roleRepository->all(),
        ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\User\Create $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\User\Create $request)
    {
        // Get request input
        $request->merge(['role' => $this->roleRepository->find($request->role)]);
        $attributes = $request->exceptNonFillable();

        // Create a user with the provided input
        $user = User::make($attributes);

        // Attempt to save user into the repository
        $created = $this->userRepository->create($user);

        // Success
        if ($created) {
            session()->flash('success', sprintf("User '%s' successfully created", $user));

            return redirect()->route('access.user.index');
        }

        // Something went wrong
        session()->flash('error', sprintf("Unable to create user '%s'", $user));

        return redirect()->back()->exceptInput('password');
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Http\Requests\User\View $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Http\Requests\User\View $request, User $user)
    {
        // Load view
        return view('modules.access.user.show')->withUser($user);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Http\Requests\User\View $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Http\Requests\User\View $request, User $user)
    {
        // Load view
        return view('modules.access.user.update')->with([
            'user' => $user,
            'roles' => $this->roleRepository->all(),
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\User\Update $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\User\Update $request, User $user)
    {
        // Get request input
        $request->merge(['role' => $this->roleRepository->find($request->role)]);
        $except = ($request->filled('password')) ? [] : ['password'];
        $attributes = $request->exceptNonFillable($except);

        // Apply changes to the user
        $user->set($attributes);

        // Attempt to update user
        $updated = $this->userRepository->update($user);

        // Success
        if ($updated) {
            session()->flash('success', sprintf("User '%s' successfully updated", $user));

            return redirect()->route('access.user.show', $user->getId());
        }

        // Something went wrong
        session()->flash('error', sprintf("Unable to update user '%s'", $user));

        return redirect()->back()->exceptInput('password');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Http\Requests\User\Delete $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Http\Requests\User\Delete $request, User $user)
    {
        // Attempt to delete user
        $deleted = $this->userRepository->delete($user);

        // Set feedback message
        if ($deleted) {
            session()->flash('success', sprintf("User '%s' successfully deleted", $user));
        } else {
            session()->flash('error', sprintf("Unable to delete user '%s'", $user));
        }

        // Return to the requesting page
        return redirect()->back();
    }
}
