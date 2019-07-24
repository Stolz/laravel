<?php

namespace Tests\Http\Controllers\Api;

use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Structure of an authentication token response.
     *
     * @const array
     */
    const TOKEN_STRUCTURE = ['access_token', 'token_type', 'expires_in'];

    /**
     * Structure of a pagination response.
     *
     * @const array
     */
    const PAGINATION_STRUCTURE = ['current_page', 'data', 'first_page_url', 'from',
    'last_page', 'last_page_url', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to'];

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Set default API request headers
        $this->withHeader('Accept', 'application/json');
    }

    /**
     * Add authentication token header to the request.
     *
     * @param  string $token
     * @return self
     */
    protected function withTokenHeader(string $token)
    {
        return $this->withHeader('Authorization', "Bearer $token");
    }

    /**
     * Disable brute-force attack protection.
     *
     * @return self
     */
    protected function withoutThrottleMiddleware()
    {
        return $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
    }
}
