<?php

namespace App\Http\Controllers;

use App\Models\Country\Country;
use App\Transformers\CountryTransformer;
use Illuminate\View\View;

class CountriesController extends APIController
{

    /**
     * Display a Listing of the Countries.
     *
     * @return mixed
     */
    public function index()
    {
    	if(!$this->api) return view('countries.index');
	    $countries = \Cache::remember('v'.$this->v.$this->api.'_countries', 2400, function() {
			return Country::get();
	    });
	    return $this->reply(fractal()->collection($countries)->transformWith(new CountryTransformer()));
    }

    /**
     * Display the Specified Country
     *
     * @param  string $id
     * @return mixed
     */
    public function show($id)
    {
	    $country = \Cache::remember('v'.$this->v.$this->api.'_country_'.$id, 2400, function() use($id) {
		    $country = Country::with('languages.bibles.filesets')->find($id);
		    if(!$country) return $this->setStatusCode(404)->replyWithError("Country not found for ID: $id");
		    if(!$this->api) return $country;
		    return $this->reply(fractal()->collection($country)->transformWith(new CountryTransformer())->ToArray());
	    });
    	return view('countries.show',compact('country'));
    }

	/**
	 * Edit the Specified Country
	 *
	 * @param $id
	 * @return View
	 */
	public function edit($id)
	{
		$country = Country::find($id);
		return view('countries.edit',compact('country'));
	}


	/**
	 * Update the Specified Country
	 *
	 * @param $id
	 *
	 * @return View
	 */
	public function update($id)
	{
		// TODO: Write UPDATE CODE
		$country = Country::find($id);
		return view('countries.show',compact('country'));
	}

}
