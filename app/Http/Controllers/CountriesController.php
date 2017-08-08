<?php

namespace App\Http\Controllers;

use App\Models\Country\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

/**
 * This class is missing the create, edit, store, and destroy methods
 * as it is this developer's hope that few changes will need to be
 * made to the geopolitical metadata. Idealism before cynicism.
 *
 */
class CountriesController extends APIController
{

    /**
     * Display a listing of the Countries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->api) {
	        $countries = Country::select('id','name')->get();
	        return fractal()->collection($countries)->transformWith(new CountryTransformer())->ToArray();
        }
        return view('countries.index');
    }

    /**
     * Display the specified country
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	if($this->api) {
		    $country = Country::find($id);
		    if(!$country) return $this->setStatusCode(404)->replyWithError("Country not found for ID: $id");
		    return fractal()->collection($country)->transformWith(new CountryTransformer())->ToArray();
	    }
	    return view('countries.show');
    }

    public function create()
    {
    	return view('countries.create');
    }
}
