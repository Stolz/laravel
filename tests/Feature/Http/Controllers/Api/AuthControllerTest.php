<?php

namespace Test\Http\Controllers\Api;

use App\Traits\AttachesRepositories;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers;

    /**
     * Structure of a JWT response.
     *
     * @const array
     */
    const JWT_JSON = ['access_token', 'token_type', 'expires_in'];

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create user
        $this->user = $this->createUser(['password' => 'secret']);

        // Create a valid JWT for the user
        $this->token = auth('api')->tokenById($this->user->getJWTIdentifier());

        // Set default API request headers
        $this->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * Test get a JWT for the given credentials.
     *
     * @return void
     */
    public function testLogin()
    {
        $route = route('api.login');

        // Test incomplete credentials
        $response = $this->post($route, []);
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
            'password' => 'secret',
        ]);
        $response->assertOk();
        $response->assertJsonStructure(static::JWT_JSON);
    }

    /**
     * Test refresh current token.
     *
     * @return void
     */
    public function testRefreshToken()
    {
        $route = route('api.refresh');

        // Test without token
        $response = $this->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with invalid token
        $token = 'invalid';
        $response = $this->withBearerHeader('invalid')->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with valid token
        $response = $this->withBearerHeader($this->token)->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::JWT_JSON);
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
        $response = $this->withBearerHeader('invalid')->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with valid token
        $response = $this->withBearerHeader($this->token)->get($route);
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
        $response = $this->withBearerHeader('invalid')->get($route);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

        // Test with valid token
        $response = $this->withBearerHeader($this->token)->get($route);
        $response->assertOk();
        $response->assertJson(['logout' => true]);
    }

    /**
     * Add bearer authentication header to the request.
     *
     * @param  string $token
     * @return self
     */
    protected function withBearerHeader(string $token)
    {
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        return $this;
    }
}
