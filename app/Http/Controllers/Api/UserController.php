<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO This is a demo controller. It intentionally lacks user authorization checks.

class UserController extends Controller
{
    /**
     * Instance of the service used to interact with users.
     *
     * @var \App\Repositories\Contracts\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('perPage', 15);
        $page = (int) $request->input('page', 1);
        $sortBy = $request->input('sortBy');
        $sortDirection = $request->input('sortDir', 'asc');

        $users = $this->userRepository->paginate($perPage, $page, $sortBy, $sortDirection)->transform(function ($user) {
            return $user->jsonSerialize();
        });

        return $this->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, \App\Repositories\Contracts\RoleRepository $roleRepository): JsonResponse
    {
        $attributes = $request->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:App\Models\User',
            'password' => 'required|min:5',
            'role' => 'required|exists:App\Models\Role,name',
        ]);

        $attributes['role'] = $roleRepository->findBy('name', $attributes['role']);
        $user = User::make($attributes);

        if ($this->userRepository->create($user))
            return $this->json(['created' => true, 'id' => $user->getId()], 201);

        return $this->json(['created' => false], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return $this->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $attributes = $request->validate([
            'name' => 'min:2|max:255',
            'email' => 'email|max:255|unique:App\Models\User,email,' . $user->getId(),
            'password' => 'min:5',
        ]);

        $user->set($attributes);
        $updated = $this->userRepository->update($user, array_keys($attributes));

        return $this->json(['updated' => $updated], $updated ? 200 : 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $deleted = $this->userRepository->delete($user);

        return $this->json(['deleted' => $deleted], $deleted ? 200 : 500);
    }
}
