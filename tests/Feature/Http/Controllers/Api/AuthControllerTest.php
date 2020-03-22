<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->user = $this->createUser(['password' => 'verysecret']);

        // Generate a valid API token for the user
        $this->token = auth('api')->tokenById($this->user->getJWTIdentifier());
    }

    /**
     * Test brute-force attack protection.
     *
     * @return void
     */
    public function testThrottle()
    {
        $route = route('api.login');
        for ($i = 1; $i <= 20; $i++) {
            $response = $this->post($route);
        }

        $response->assertStatus(429);
    }

    /**
     * Test login into the API.
     *
     * @return void
     */
    public function testLogin()
    {
        // Disable brute-force attack protection
        $this->withoutThrottleMiddleware();
        $route = route('api.login');

        // Test incomplete credentials
        $response = $this->post($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);

        // Test invalid credentials
        $response = $this->post($route, [
            'email' => 'test@example.com',
            'password' => 'random',
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure(['error']);

        // Test valid credentials
        $response = $this->post($route, [
            'email' => $this->user->getEmail(),
            'password' => 'verysecret',
        ]);
        $response->assertOk();
        $response->assertJsonStructure(static::TOKEN_STRUCTURE);
    }

    /**
     * Test refresh current token.
     *
     * @return void
     */
    public function testRefreshToken()
    {
        // Disable brute-force attack protection
        $this->withoutThrottleMiddleware();
        $route = route('api.refresh');

        // Test without token
        $response = $this->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with invalid token
        $response = $this->withTokenHeader('invalid')->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with valid token
        $response = $this->withTokenHeader($this->token)->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::TOKEN_STRUCTURE);
    }

    /**
     * Test show current authenticated user.
     *
     * @return void
     */
    public function testMe()
    {
        $route = route('api.me');

        // Test without token
        $response = $this->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with invalid token
        $response = $this->withTokenHeader('invalid')->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with valid token
        $response = $this->withTokenHeader($this->token)->get($route);
        $response->assertOk();
        $response->assertJson($this->user->jsonSerialize());
    }

    /**
     * Test logout the user.
     *
     * @return void
     */
    public function testLogout()
    {
        $route = route('api.logout');

        // Test without token
        $response = $this->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with invalid token
        $response = $this->withTokenHeader('invalid')->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with valid token
        $response = $this->withTokenHeader($this->token)->get($route);
        $response->assertOk();
        $response->assertJson(['logout' => true]);
    }
}
