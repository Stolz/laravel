<?php

namespace Tests\Browser\Http\Controllers;

use App\Models\User;
use App\Traits\AttachesRepositories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MeControllerTest extends DuskTestCase
{
    use DatabaseMigrations, AttachesRepositories;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create test role
        $role = \App\Models\Role::make(['name' => str_random(6)]);
        $this->roleRepository->create($role);

        // Create test user
        $this->user = factory(User::class)->make(['password' => 'secret', 'role' => $role]);
        $this->userRepository->create($this->user);
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
            ->press(_('Change password'))
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
            ->press(_('Change password'))
            ->assertRouteIs('me.password')
            ->assertSee(_('Wrong password'));
        });
    }
}
