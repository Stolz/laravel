<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
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
            'password' => 'required|min:' . User::MIN_PASSWORD_LENGTH,
        ]);

        if (! $token = auth('api')->attempt($credentials))
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
        $token = auth('api')->refresh();

        return $this->respondWithToken($token);
    }

    /**
     * Show current authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->json(auth('api')->user());
    }

    /**
     * Logout the user, thus invalidating the token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

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
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}