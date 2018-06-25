<?php

namespace Tests\Browser\Http\Middleware;

use App\Traits\AttachesRepositories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RedirectIfAuthenticatedTest extends DuskTestCase
{
    use DatabaseMigrations, AttachesRepositories;

    /**
     * Create a test user.
     *
     * @return self
     */
    protected function createUser()
    {
        // Create test role
        $role = \App\Models\Role::make(['name' => str_random(6)]);
        $this->roleRepository->create($role);

        // Create test user
        $this->user = factory(\App\Models\User::class)->make(['password' => 'secret', 'role' => $role]);
        $this->userRepository->create($this->user);

        return $this;
    }

    /**
     * Test guests cannot access authenticated users area.
     *
     * @return void
     */
    public function testGuestAccess()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->visit(route('me'))
            ->assertRouteIs('login');
        });
    }

    /**
     * Test authenticated users cannot access guest area.
     *
     * @return void
     */
    public function testAuthAccess()
    {
        $this->createUser()->browse(function (Browser $browser) {
            $browser
            ->loginAs($this->user->getAuthIdentifier())
            ->visit(route('login'))
            ->assertRouteIs('me');
        });
    }

    /**
     * Test redirect to intended after success login.
     *
     * @return void
     */
    public function testRedirectToIntended()
    {
        $this->createUser()->browse(function (Browser $browser) {
            $browser
            ->visit(route('me.password'))
            ->assertRouteIs('login')
            ->type('email', $this->user->getEmail())
            ->type('password', 'secret')
            ->press(_('Login'))
            ->assertRouteIs('me.password');
        });
    }
}
