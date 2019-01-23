<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    /**
     * Instance of the service used to interact with permissions.
     *
     * @var \App\Repositories\Contracts\PermissionRepository
     */
    protected $permissionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\PermissionRepository $permissionRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a list of permissions.
     *
     * @param  \App\Http\Requests\Permission\Index $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(\App\Http\Requests\Permission\Index $request): JsonResponse
    {
        // Get sorting options
        list($sortBy, $sortDirection) = $this->getSortingOptionsFromRequest($request, 'name');

        // Get permissions from repository
        $permissions = $this->permissionRepository->all([$sortBy => $sortDirection]);

        return $this->json($permissions);
    }
}
