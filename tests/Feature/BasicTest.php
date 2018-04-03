<?php

namespace Tests\Feature;

use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Tests home page is accessible.
     *
     * @return void
     */
    public function testHomePage()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    /**
     * Test routes can be parsed.
     *
     * @return void
     */
    public function testRoutes()
    {
        $this->artisan('route:list');
        $output = app('Illuminate\Contracts\Console\Kernel')->output();
        $this->assertRegExp('/Domain.*Method.*URI.*Name.*Action.*Middleware/', $output);
    }
}
