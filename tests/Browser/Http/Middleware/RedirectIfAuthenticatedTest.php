<?php

namespace Tests\Browser\Http\Middleware;

use App\Traits\AttachesRepositories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\CreatesUsers;

class RedirectIfAuthenticatedTest extends DuskTestCase
{
    use DatabaseMigrations, AttachesRepositories, CreatesUsers;

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
        $this->browse(function (Browser $browser) {
            $user = $this->createUser();

            $browser
            ->loginAs($user->getAuthIdentifier())
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
        $this->browse(function (Browser $browser) {
            $user = $this->createUser();

            $browser
            ->visit(route('me.password'))
            ->assertRouteIs('login')
            ->type('email', $user->getEmail())
            ->type('password', 'secret')
            ->press('[type="submit"]')
            ->assertRouteIs('me.password');
        });
    }
}
