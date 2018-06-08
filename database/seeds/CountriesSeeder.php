<?php

use App\Models\Country;
use App\Repositories\Contracts\CountryRepository;
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
        $countryRepository = app(CountryRepository::class);

        $countries = [
            ['name' => 'Hong Kong'],
            ['name' => 'Spain'],
        ];

        foreach ($countries as $country)
            $countryRepository->create(Country::make($country));
    }
}

