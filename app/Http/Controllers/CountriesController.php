<?php

namespace App\Http\Controllers;

use App\Models\Country\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

/**
 * This class is missing the create, store, and destroy methods
 * as it is this developer's hope that few changes will need to be
 * made to the geopolitical metadata. Idealism before cynicism.
 *
 */
class CountriesController extends APIController
{

    /**
     * Display a Listing of the Countries.
     *
     * @return JSON|View
     */
    public function index()
    {
    	if(!$this->api) return view('countries.index');

		$countries = Country::select('id','name')->get();
		return fractal()->collection($countries)->transformWith(new CountryTransformer())->ToArray();
    }

    /**
     * Display the Specified Country
     *
     * @param  int|string|char(3) $id
     * @return JSON|View
     */
    public function show($id)
    {
	    $country = Country::find($id);
	    if(!$country) return $this->setStatusCode(404)->replyWithError("Country not found for ID: $id");
    	if(!$this->api) return view('countries.show',compact('country'));

		return fractal()->collection($country)->transformWith(new CountryTransformer())->ToArray();
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
	 * @param Request $request
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function update(Request $request,$id)
	{
		$country = Country::find($id);
		return view('countries.show',compact('country'));
	}

}
