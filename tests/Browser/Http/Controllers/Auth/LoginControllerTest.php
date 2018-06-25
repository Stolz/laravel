<?php

namespace Tests\Browser\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\AttachesRepositories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginControllerTest extends DuskTestCase
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
     * Test log in with valid credentials.
     *
     * @return void
     */
    public function testSuccessLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->visit(route('login'))
            ->type('email', $this->user->getEmail())
            ->type('password', 'secret')
            ->press(_('Login'))
            ->assertRouteIs('me')
            ->assertSee(_('You are logged in!'))
            ->assertAuthenticatedAs($this->user);
        });
    }

    /**
     * Test log in with invalid credentials.
     *
     * @return void
     */
    public function testWrongLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->visit(route('login'))
            ->type('email', $this->user->getEmail())
            ->type('password', str_random(10))
            ->press(_('Login'))
            ->assertRouteIs('login')
            ->assertSee(_('These credentials do not match our records'))
            ->assertGuest();
        });
    }

    /**
     * Test log out.
     *
     * @return void
     */
    public function testLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->loginAs($this->user->getAuthIdentifier())
            ->visit(route('logout'))
            ->assertRouteIs('home')
            ->assertGuest();
        });
    }
}
