<?php

namespace Tests\Feature;

use App\Traits\AttachesRepositories;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class MasterModuleTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, RejectsUnauthorizedRouteAccess;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create user with no permissions
        $this->user = $this->createUser();

        // Create user with all permissions
        $this->admin = $this->createUser([], ['name' => 'Admin']);
    }

    /**
     * Tests home page of the module.
     *
     * @return void
     */
    public function testHomePage()
    {
        // User without permissions
        $route = route('master.home');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Master module');
    }

    /**
     * Tests countries list page.
     *
     * @return void
     */
    public function testCountryIndex()
    {
        // User without permissions
        $route = route('master.country.index');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Back to module');
    }

    /**
     * Tests create country page.
     *
     * @return void
     */
    public function testCountryCreateForm()
    {
        // User without permissions
        $route = route('master.country.create');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests create country action.
     *
     * @return void
     */
    public function testCountryCreate()
    {
        // User without permissions
        $route = route('master.country.store');
        $response = $this->rejectUnauthorizedRouteAccess($route, 'post');

        // User with permissions. Incomplete data
        $referer = route('master.country.create');
        $response = $this->actingAs($this->admin)->from($referer)->post($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\Country::class)->raw();

        $response = $this->post($route, $data);
        $response->assertRedirect(route('master.country.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests show country page.
     *
     * @return void
     */
    public function testCountryShow()
    {
        // Create a country
        $country = factory(\App\Models\Country::class)->make();
        $this->countryRepository->create($country);

        // User without permissions
        $route = route('master.country.show', [$country->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Return');
    }

    /**
     * Tests update country page.
     *
     * @return void
     */
    public function testCountryUpdateForm()
    {
        // Create a country
        $country = factory(\App\Models\Country::class)->make();
        $this->countryRepository->create($country);

        // User without permissions
        $route = route('master.country.edit', [$country->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests update country action.
     *
     * @return void
     */
    public function testCountryUpdate()
    {
        // Create a country
        $country = factory(\App\Models\Country::class)->make();
        $this->countryRepository->create($country);

        // User without permissions
        $route = route('master.country.update', [$id = $country->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'put');

        // User with permissions. Incomplete data
        $referer = route('master.country.edit', [$id]);
        $response = $this->actingAs($this->admin)->from($referer)->put($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\Country::class)->raw();

        $response = $this->put($route, $data);
        $response->assertRedirect(route('master.country.show', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests delete country action.
     *
     * @return void
     */
    public function testCountryDelete()
    {
        // Create a country
        $country = factory(\App\Models\Country::class)->make();
        $this->countryRepository->create($country);

        // User without permissions
        $route = route('master.country.destroy', [$country->getId()]);
        $response = $this->rejectUnauthorizedRouteAccess($route, 'delete');

        // User with permissions
        $referer = route('master.country.index');
        $response = $this->actingAs($this->admin)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
