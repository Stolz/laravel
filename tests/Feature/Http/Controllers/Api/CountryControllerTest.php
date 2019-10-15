<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Country;
use App\Traits\AttachesRepositories;
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
    }

    /**
     * Tests display a list of countries.
     *
     * @return void
     */
    public function testIndex()
    {
        // User without permissions
        $route = route('api.country.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'country-list']]);
        $this->actingAs($user, 'api');

        // Test empty list
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(0, 'data');

        // Create test countries
        $countryFoo = $this->createCountry();
        $countryBar = $this->createCountry();

        // Test non empty list
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(2, 'data');

        // Test sorting results
        $route = route('api.country.index', ['sort_by' => 'id', 'sort_dir' => 'desc']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(2, 'data');
        $this->assertEquals($countryBar->getId(), $response->json('data.0.id'));
        $this->assertEquals($countryFoo->getId(), $response->json('data.1.id'));
    }

    /**
     * Test display the specified country.
     *
     * @return void
     */
    public function testShow()
    {
        // User without permissions
        $country = $this->createCountry();
        $route = route('api.country.show', $country->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'country-view']]);
        $this->actingAs($user, 'api');

        // Test existing country
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($country->jsonSerialize());

        // Test non existing country
        $route = route('api.country.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();
    }

    /**
     * Tests store a newly created country in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('api.country.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'country-create']]);
        $this->actingAs($user, 'api');

        // Test with incomplete data
        $response = $this->post($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test with complete data
        $data = factory(Country::class)->raw();
        $response = $this->post($route, $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'created']);
        $response->assertJson(['created' => true]);
    }

    /**
     * Tests update the specified country in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // User without permissions
        $country = $this->createCountry();
        $route = route('api.country.update', $country->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'country-update']]);
        $this->actingAs($user, 'api');

        // Test existing country with incomplete data
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing country with complete data
        $data = factory(Country::class)->raw();
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);

        // Test non existing country
        $route = route('api.country.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();
    }

    /**
     * Tests remove the specified country from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // User without permissions
        $country = $this->createCountry();
        $route = route('api.country.destroy', $country->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'country-delete']]);
        $this->actingAs($user, 'api');

        // Test existing country
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);

        // Test non existing country
        $route = route('api.country.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();
    }
}
