<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Country;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesCountries;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class CountryControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, CreatesCountries;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create user with all permissions ...
        $this->user = $this->createUser([], ['name' => 'Admin']);

        // ... and authenticate it
        $this->actingAs($this->user, 'api');
    }

    /**
     * Tests display a list of countries.
     *
     * @return void
     */
    public function testIndex()
    {
        // Test empty list
        $route = route('api.country.index');
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
    }

    /**
     * Test display the specified country.
     *
     * @return void
     */
    public function testShow()
    {
        // Test non existing country
        $route = route('api.country.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();

        // Test existing country
        $country = $this->createCountry();
        $route = route('api.country.show', $country->getId());
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($country->jsonSerialize());
    }

    /**
     * Tests store a newly created country in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // Test with incomplete data
        $route = route('api.country.store', ['random']);
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
        // Test non existing country
        $route = route('api.country.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();

        // Test existing country with incomplete data
        $country = $this->createCountry();
        $route = route('api.country.update', $country->getId());
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing country with complete data
        $data = factory(Country::class)->raw();
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);
    }

    /**
     * Tests remove the specified country from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Test non existing country
        $route = route('api.country.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();

        // Test existing country
        $country = $this->createCountry();
        $route = route('api.country.destroy', $country->getId());
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);
    }
}
