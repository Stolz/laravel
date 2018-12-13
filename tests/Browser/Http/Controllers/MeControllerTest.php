<?php

namespace Tests\Browser\Http\Controllers;

use App\Traits\AttachesRepositories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\CreatesUsers;

class MeControllerTest extends DuskTestCase
{
    use DatabaseMigrations, AttachesRepositories, CreatesUsers;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create test user
        $this->user = $this->createUser();
    }

    /**
     * Test change user password.
     *
     * @return void
     */
    public function testChangePassword()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->loginAs($this->user->getAuthIdentifier())
            ->visit(route('me.password'))
            ->type('password', 'secret')
            ->type('new_password', 'supersecret')
            ->type('new_password_confirmation', 'supersecret')
            ->press('[type="submit"]')
            ->assertRouteIs('me')
            ->assertSee(_('Password successfully changed'));
        });
    }

    /**
     * Test change wrong password.
     *
     * @return void
     */
    public function testChangeWrongPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->loginAs($this->user->getAuthIdentifier())
            ->visit(route('me.password'))
            ->type('password', str_random(10))
            ->type('new_password', 'supersecret')
            ->type('new_password_confirmation', 'supersecret')
            ->press('[type="submit"]')
            ->assertRouteIs('me.password')
            ->assertSee(_('Wrong password'));
        });
    }
}
