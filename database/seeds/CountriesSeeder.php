<?php

use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $countryRepository = app(App\Repositories\Contracts\CountryRepository::class);

        $countries = [
            ['name' => 'Hong Kong'],
            ['name' => 'Spain'],
        ];

        foreach ($countries as $country) {
            $countryRepository->create(App\Models\Country::make($country));
        }
    }
}
