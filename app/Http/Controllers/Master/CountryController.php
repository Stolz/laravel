<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Country;

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
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\Country\Index $request)
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'name');

        // Get countries from repository
        $countries = $this->countryRepository->paginate($perPage, $page, $sortBy, $sortDirection);

        // Load view
        return view('modules.master.country.index')->withCountries($countries);
    }

    /**
     * Display the specified country.
     *
     * @param  \App\Http\Requests\Country\View $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Http\Requests\Country\View $request, Country $country)
    {
        // Load view
        return view('modules.master.country.show')->withCountry($country);
    }

    /**
     * Show the form for creating a new country.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Create an empty country
        $country = Country::make();

        // Load view
        return view('modules.master.country.create')->withCountry($country);
    }

    /**
     * Show the form for editing the specified country.
     *
     * @param  \App\Http\Requests\Country\View $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Http\Requests\Country\View $request, Country $country)
    {
        // Load view
        return view('modules.master.country.update')->withCountry($country);
    }

    /**
     * Store a newly created country in storage.
     *
     * @param  \App\Http\Requests\Country\Create $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Country\Create $request)
    {
        // Get request input
        $attributes = $request->only('name');

        // Create a country with the provided input
        $country = Country::make($attributes);

        // Attempt to save country into the repository
        $created = $this->countryRepository->create($country);

        // Success
        if ($created) {
            session()->flash('success', sprintf(_("Country '%s' successfully created"), $country));

            return redirect()->route('master.country.index');
        }

        // Something went wrong
        session()->flash('error', sprintf(_("Unable to create country '%s'"), $country));

        return redirect()->back()->withInput();
    }

    /**
     * Update the specified country in storage.
     *
     * @param  \App\Http\Requests\Country\Update $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Country\Update $request, Country $country)
    {
        // Get request input
        $attributes = $request->only('name');

        // Apply changes to the country
        $country->set($attributes);

        // Attempt to update country
        $updated = $this->countryRepository->update($country);

        // Success
        if ($updated) {
            session()->flash('success', sprintf(_("Country '%s' successfully updated"), $country));

            return redirect()->route('master.country.show', $country->getId());
        }

        // Something went wrong
        session()->flash('error', sprintf(_("Unable to update country '%s'"), $country));

        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified country from storage.
     *
     * @param  \App\Http\Requests\Country\Delete $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Http\Requests\Country\Delete $request, Country $country)
    {
        // Attempt to delete country
        $deleted = $this->countryRepository->delete($country);

        // Set feedback message
        if ($deleted) {
            session()->flash('success', sprintf(_("Country '%s' successfully deleted"), $country));
        } else {
            session()->flash('error', sprintf(_("Unable to delete country '%s'"), $country));
        }

        // Return to the requesting page
        return redirect()->back();
    }
}
