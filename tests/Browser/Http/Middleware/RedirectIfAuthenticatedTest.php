<?php

namespace Tests\Browser\Http\Middleware;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RedirectIfAuthenticatedTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Create a test user.
     *
     * @return self
     */
    protected function createUser()
    {
        $this->user = factory(\App\Models\User::class)->make(['password' => 'secret']);
        $userRepository = app(\App\Repositories\Contracts\UserRepository::class);
        $userRepository->create($this->user);

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
