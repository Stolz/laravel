<?php

namespace Tests\Traits;

use App\Models\Country;

trait CreatesCountries
{
    /**
     * Create a test country.
     *
     * @param  array $attributes
     * @return \App\Models\Country
     */
    protected function createCountry(array $attributes = []): Country
    {
        // Create country
        $country = factory(Country::class)->make($attributes);
        $this->countryRepository->create($country);

        return $country;
    }
}
