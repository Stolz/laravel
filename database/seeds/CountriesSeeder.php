<?php

use App\Models\Country;

class CountriesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['name' => 'Hong Kong'],
            ['name' => 'Spain'],
        ];

        foreach ($countries as $country) {
            $country = Country::make($country);
            $this->countryRepository->create($country);
        }
    }
}
