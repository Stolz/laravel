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
    public function __construct(
        \App\Repositories\Contracts\RoleRepository $roleRepository,
        \App\Repositories\Contracts\UserRepository $userRepository
    ) {
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
        list($searchCriteria, $perPage, $page, $sortBy, $sortDirection) = $this->getSearchPaginationOptionsFromRequest($request, 15, 'name');

        // Get users from repository
        $includeSoftDeleted = (bool) $request->input('search.all');
        $users = $this->userRepository->includeSoftDeleted($includeSoftDeleted)->paginateSearch($searchCriteria, $perPage, $page, $sortBy, $sortDirection);

        // Load view
        return view('modules.access.user.index')->with([
            'roles' => $this->roleRepository->all()->prepend(_('Any')),
            'users' => $users,
        ]);
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
     * Show the form for creating a new user.
     *
     * @param  \App\Http\Requests\User\PreCreate $request
     * @return \Illuminate\Http\Response
     */
    public function create(\App\Http\Requests\User\PreCreate $request)
    {
        // Load view
        return view('modules.access.user.create')->with([
            'minPasswordLength' => User::MIN_PASSWORD_LENGTH,
            'roles' => $this->roleRepository->all(),
            'timezones' => array_combine($timezones = timezone_identifiers_list(), $timezones),
            'user' => User::make(),
        ]);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Http\Requests\User\PreUpdate $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Http\Requests\User\PreUpdate $request, User $user)
    {
        // Load view
        return view('modules.access.user.update')->with([
            'roles' => $this->roleRepository->all(),
            'timezones' => array_combine($timezones = timezone_identifiers_list(), $timezones),
            'user' => $user,
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
        $attributes = $request->merge([
            'role' => $this->roleRepository->find($request->role),
        ])->exceptNonFillable();

        // Create a user with the provided input
        $user = User::make($attributes);

        // Attempt to save user into the repository
        $created = $this->userRepository->create($user);

        // Success
        if ($created) {
            return redirect()->route('access.user.index')->with('success', sprintf(_("User '%s' successfully created"), $user));
        }

        // Something went wrong
        return redirect()->back()->exceptInput('password')->with('error', sprintf(_("Unable to create user '%s'"), $user));
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
        $except = ($request->filled('password')) ? [] : ['password'];
        $attributes = $request->merge([
            'role' => $this->roleRepository->find($request->role),
        ])->exceptNonFillable($except);

        // Apply changes to the user
        $user->set($attributes);

        // Attempt to update user
        $updated = $this->userRepository->update($user);

        // Success
        if ($updated) {
            return redirect()->route('access.user.show', $user->getId())->with('success', sprintf(_("User '%s' successfully updated"), $user));
        }

        // Something went wrong
        return redirect()->back()->exceptInput('password')->with('error', sprintf(_("Unable to update user '%s'"), $user));
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

        // Something went wrong
        if (! $deleted) {
            return redirect()->back()->with('error', sprintf(_("Unable to delete user '%s'"), $user));
        }

        // Success
        $redirectBack = ($request->input('_from') === 'access.user.show') ? redirect()->route('access.user.index') : redirect()->back();

        return $redirectBack->with('success', sprintf(_("User '%s' successfully deleted"), $user));
    }
}
