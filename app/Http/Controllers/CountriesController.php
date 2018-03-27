<?php

namespace App\Http\Controllers;

use App\Models\Country\JoshuaProject;
use App\Models\Language\Language;
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
    public function index($id = null)
    {
    	if(!$this->api) return view('countries.index');
    	// \Cache::forget('v'.$this->v.$this->api.'_countries');
	    // $countries = \Cache::remember('v'.$this->v.$this->api.'_countries', 2400, function() {
		$countries = Country::with('languagesFiltered','translations')->get();
		//	$countries = Country::raw('
		//	SELECT countries.name,countries.continent, GROUP_CONCAT(CONCAT(languages.iso,languages.name))
		//	AS languages,countries.fips,countries.iso_a3,countries.id FROM languages,countries,country_language)
		//	WHERE languages.id=country_language.language_id AND country_language.country_id=countries.id;')->get();

	    return $this->reply(fractal()->collection($countries)->transformWith(new CountryTransformer()));
    }

    public function joshuaProjectIndex()
    {
	    $iso = (isset($_GET['iso'])) ? $_GET['iso'] : 'eng';
	    $language = Language::where('iso', $iso)->first();
	    $countries = JoshuaProject::with(['country.translation' => function ($query) use ($language) {
		    $query->where('language_id', $language->id);
	    }])->get();

	    return $this->reply(fractal()->collection($countries)->transformWith(CountryTransformer::class));
    }

    /**
     * Display the Specified Country
     *
     * @param  string $id
     * @return mixed
     */
    public function show($id)
    {
		$country = Country::with('languagesFiltered.bibles.currentTranslation')->find($id);
	    if(!$country) return $this->setStatusCode(404)->replyWithError("Country not found for ID: $id");
	    return $this->reply(fractal()->item($country)->transformWith(new CountryTransformer())->ToArray());

    	return view('countries.show',compact('country'));
    }

	public function create()
	{
		return view('countries.create');
	}

	public function store(Request $request)
	{
		$validator = $request->validate([
			'id'                  => 'string|max:2|min:2|required',
			'iso_a3'              => 'string|max:3|min:3|required',
			'fips'                => 'string|max:2|min:2|required',
			'continent'           => 'string|max:2|min:2|required',
			'name'                => 'string|max:191|required',
			'introduction'        => 'string|min:6|nullable',
		]);



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
