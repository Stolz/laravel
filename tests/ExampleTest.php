<?php

namespace Tests;

class ExampleTest extends TestCase
{
    /**
     * Test template.
     *
     * @return void
     */
    /*public function testSomething()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }*/

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * A basic HTTP functional test example.
     *
     * @return void
    */
    public function testHomePage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test routes are correct.
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
