<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    /**
     * Instance of the service used to interact with countries.
     *
     * @var \App\Repositories\Contracts\CountryRepository
     */
    protected $countryRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\CountryRepository $countryRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Display a list of countries.
     *
     * @param  \App\Http\Requests\Country\Index $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(\App\Http\Requests\Country\Index $request): JsonResponse
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'name');

        // Get countries from repository
        $countries = $this->countryRepository->paginate($perPage, $page, $sortBy, $sortDirection)->transform(function ($country) {
            return $country->jsonSerialize();
        });

        return $this->json($countries);
    }

    /**
     * Display the specified country.
     *
     * @param  \App\Http\Requests\Country\View $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(\App\Http\Requests\Country\View $request, Country $country): JsonResponse
    {
        return $this->json($country);
    }

    /**
     *  Store a newly created country in storage.
     *
     * @param  \App\Http\Requests\Country\Create $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\App\Http\Requests\Country\Create $request): JsonResponse
    {
        // Get request input
        $attributes = $request->only('name', 'code');

        // Create a country with the provided input
        $country = Country::make($attributes);

        // Attempt to save country into the repository
        $created = $this->countryRepository->create($country);

        // Success
        if ($created)
            return $this->json(['created' => true, 'id' => $country->getId()], 201);

        // Something went wrong
        return $this->json(['created' => false], 500);
    }

    /**
     * Update the specified country in storage.
     *
     * @param  \App\Http\Requests\Country\Update $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\App\Http\Requests\Country\Update $request, Country $country): JsonResponse
    {
        // Get request input
        $attributes = $request->only('name', 'code');

        // Apply changes to the country
        $country->set($attributes);

        // Attempt to update country
        $updated = $this->countryRepository->update($country);

        return $this->json(['updated' => $updated], $updated ? 200 : 500);
    }

    /**
     * Remove the specified country from storage.
     *
     * @param  \App\Http\Requests\Country\Delete $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(\App\Http\Requests\Country\Delete $request, Country $country): JsonResponse
    {
        // Delete country from repository
        $deleted = $this->countryRepository->delete($country);

        return $this->json(['deleted' => $deleted], $deleted ? 200 : 500);
    }
}
