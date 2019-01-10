<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * The API guard instance.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $guard;

    /**
     * Class constructor
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(\Illuminate\Contracts\Auth\Factory $auth)
    {
        $this->guard = $auth->guard('api');
    }

    /**
     * Get a JWT for the given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! $token = $this->guard->attempt($credentials))
            return $this->json(['error' => 'Unauthorized'], 401);

        return $this->respondWithToken($token);
    }

    /**
     * Refresh current token to prevent it from expiring.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $token = $this->guard->refresh();

        return $this->respondWithToken($token);
    }

    /**
     * Show current authenticated user basic information.
     *
     * @param  \App\Repositories\Contracts\PermissionRepository $permissionRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(\App\Repositories\Contracts\PermissionRepository $permissionRepository): JsonResponse
    {
        $user = $this->guard->user();
        $response = $user->jsonSerialize();

        // Add permission information
        $response['permissions'] = ($user->isSuperAdmin()) ? $permissionRepository->all()->pluck('name') : $user->getRole()->getPermissionsNames();

        return $this->json($response);
    }

    /**
     * Logout the user, thus invalidating the token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->guard->logout();

        return $this->json(['logout' => true]);
    }

    /**
     * Get the token JSON structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return $this->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard->factory()->getTTL() * 60,
        ]);
    }
}
