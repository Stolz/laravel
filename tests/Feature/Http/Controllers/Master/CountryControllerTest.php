<?php

namespace Tests\Feature\Http\Controllers\Master;

use App\Traits\AttachesRepositories;
use Tests\TestCase;
use Tests\Traits\CreatesCountries;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class CountryControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, CreatesCountries, RejectsUnauthorizedRouteAccess;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create user with no permissions
        $this->user = $this->createUser();

        // Create user with all permissions
        $this->admin = $this->createUser([], ['name' => 'Admin']);
    }

    /**
     * Tests display a list of countries.
     *
     * @return void
     */
    public function testIndex()
    {
        // User without permissions
        $route = route('master.country.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON index');
    }

    /**
     * Test display the specified country.
     *
     * @return void
     */
    public function testShow()
    {
        // Create a country
        $country = $this->createCountry();

        // User without permissions
        $route = route('master.country.show', [$country->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Return');
    }

    /**
     * Test show the form for creating a new country.
     *
     * @return void
     */
    public function testCreate()
    {
        // User without permissions
        $route = route('master.country.create');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests show the form for editing the specified country.
     *
     * @return void
     */
    public function testEdit()
    {
        // Create a country
        $country = $this->createCountry();

        // User without permissions
        $route = route('master.country.edit', [$country->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
        $response->assertSee('Cancel');
    }

    /**
     * Tests store a newly created country in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('master.country.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post');

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
     * Tests update the specified country in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // Create a country
        $country = factory(\App\Models\Country::class)->make();
        $this->countryRepository->create($country);

        // User without permissions
        $route = route('master.country.update', [$id = $country->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put');

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
     * Tests remove the specified country from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Create a country
        $country = factory(\App\Models\Country::class)->make();
        $this->countryRepository->create($country);

        // User without permissions
        $route = route('master.country.destroy', [$country->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete');

        // User with permissions
        $referer = route('master.country.index');
        $response = $this->actingAs($this->admin)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
