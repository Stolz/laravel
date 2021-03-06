<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(\App\Http\Requests\User\Index $request): JsonResponse
    {
        // Get pagination options
        list($searchCriteria, $perPage, $page, $sortBy, $sortDirection) = $this->getSearchPaginationOptionsFromRequest($request, 15, 'name');

        // Get users from repository
        $users = $this->userRepository->paginateSearch($searchCriteria, $perPage, $page, $sortBy, $sortDirection);

        return $this->json($users);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Http\Requests\User\View $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(\App\Http\Requests\User\View $request, User $user): JsonResponse
    {
        return $this->json($user);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\User\Create $request
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\App\Http\Requests\User\Create $request, \App\Repositories\Contracts\RoleRepository $roleRepository): JsonResponse
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
            return $this->json(['created' => true, 'id' => $user->getId()], 201);
        }

        // Something went wrong
        return $this->json(['created' => false], 500);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\User\Update $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\App\Http\Requests\User\Update $request, User $user): JsonResponse
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

        return $this->json(['updated' => $updated], $updated ? 200 : 500);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Http\Requests\User\Delete $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(\App\Http\Requests\User\Delete $request, User $user): JsonResponse
    {
        // Delete user from repository
        $deleted = $this->userRepository->delete($user);

        return $this->json(['deleted' => $deleted], $deleted ? 200 : 500);
    }
}
