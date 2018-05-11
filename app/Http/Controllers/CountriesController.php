<?php

namespace App\Http\Controllers;

use App\Models\Country\JoshuaProject;
use App\Models\Language\Language;
use App\Models\Country\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;

class CountriesController extends APIController
{

	/**
	 * Returns Countries
	 *
	 * @version 4
	 * @category v4_countries.all
	 * @link http://bible.build/countries - V4 Access
	 * @link https://api.dbp.dev/countries?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 * @OAS\Get(
	 *     path="/countries/",
	 *     tags={"Version 4"},
	 *     summary="Returns Countries",
	 *     description="Returns the List of Countries",
	 *     operationId="v4_countries.all",
	 *     @OAS\Parameter(name="iso", in="query", description="", @OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OAS\Parameter(name="has_filesets", in="query", description="Filter the returned countries to only those containing filesets for languages spoken within the country", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="bucket_id", in="query", description="Filter the returned countries to only those containing filesets for a specific bucket", @OAS\Schema(ref="#/components/schemas/Bucket/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_countries.all")
	 *         )
	 *     )
	 * )
	 *
	 *
	 */
    public function index()
    {
    	if(!$this->api) return view('countries.index');
    	$iso = checkParam('iso', null, 'optional') ?? "eng";
    	$has_filesets = checkParam('has_filesets', null, 'optional') ?? true;
		$bucket_id = checkParam('bucket_id', null, 'optional');

		$countries = Country::with(['languagesFiltered','translations' => function($query) use ($iso) {
			$query->where('language_id', $iso);
		}])
		->when($has_filesets, function($q) use ($bucket_id) {
			$q->whereHas('languages.bibles.filesets', function ($query) use ($bucket_id) {
				if($bucket_id) $query->where('bucket_id', $bucket_id);
			});
		})->get();

	    return $this->reply(fractal()->collection($countries)->transformWith(new CountryTransformer()));
    }

	/**
	 * Returns Joshua Project Country Information
	 *
	 * @version 4
	 * @category v4_countries.jsp
	 * @link http://bible.build/countries/joshua-project/ - V4 Access
	 * @link https://api.dbp.dev/countries/joshua-project?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
	 *
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
    public function joshuaProjectIndex()
    {
	    $iso = (isset($_GET['iso'])) ? $_GET['iso'] : 'eng';
	    $language = Language::where('iso', $iso)->first();
	    $countries = JoshuaProject::with(['translations' => function ($query) use ($language) {
		    $query->where('language_id', $language->id);
	    }])->get();

	    return $this->reply(fractal()->collection($countries)->transformWith(CountryTransformer::class));
    }

	/**
	 * Returns the Specified Country
	 *
	 * @version 4
	 * @category v4_countries.one
	 * @link http://bible.build/countries/RU/ - V4 Access
	 * @link https://api.dbp.dev/countries/ru?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_countries_one - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/countries/{id}",
	 *     tags={"Version 4"},
	 *     summary="Returns a single Country",
	 *     description="Returns a single Country",
	 *     operationId="v4_countries.one",
	 *     @OAS\Parameter( name="id", in="path", description="The country ID", required=true, @OAS\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_countries.one")
	 *         )
	 *     )
	 * )
	 *
	 * @param  string $id
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
    public function show($id)
    {
		$country = Country::with('languagesFiltered.bibles.currentTranslation','geography')->find($id);
	    if(!$country) return $this->setStatusCode(404)->replyWithError("Country not found for ID: $id");
	    if($this->api) return $this->reply(fractal()->item($country)->transformWith(new CountryTransformer())->ToArray());

    	return view('countries.show',compact('country'));
    }

	/**
	 * Create a new Country
	 *
	 * @version 4
	 * @category ui_countries.create
	 * @link http://bible.build/countries/RU/ - V4 Access
	 * @link https://api.dbp.dev/countries/ru?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_countries_one - V4 Test Docs
	 *
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function create()
	{
		$this->validateUser();
		return view('countries.create');
	}

	/**
	 * Store a new Country
	 *
	 * @version 4
	 *
	 * @OAS\Post(
	 *     path="/countries/",
	 *     tags={"Version 4"},
	 *     summary="Create a new Country",
	 *     description="Create a new Country",
	 *     operationId="v4_countries.store",
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_countries.one")
	 *         )
	 *     )
	 * )
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
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
	 * @OAS\Put(
	 *     path="/countries/{id}",
	 *     tags={"Version 4"},
	 *     summary="Update a new Country",
	 *     description="Update a new Country",
	 *     operationId="v4_countries.update",
	 *     @OAS\Parameter( name="id", in="path", description="The country ID", required=true, @OAS\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_countries.one")
	 *         )
	 *     )
	 * )
	 *
	 * @param $id
	 * @return View
	 */
	public function update($id)
	{
		$this->validateUser();
		$this->validateCountry(request());

		$country = Country::find($id);

		if($this->api) return $this->reply("Country Succesfully updated");
		return view('countries.show',compact('country'));
	}


	/**
	 * Ensure the current User has permissions to alter the countries
	 *
	 * @param null $user
	 *
	 * @return \App\Models\User\User|mixed|null
	 */
	private function validateUser($user = null)
	{
		if(!$this->api) $user = Auth::user();
		if(!$user) {
			$key = Key::where('key',$this->key)->first();
			if(!isset($key)) return $this->setStatusCode(403)->replyWithError('No Authentication Provided or invalid Key');
			$user = $key->user;
		}
		if(!$user->archivist AND !$user->admin) return $this->setStatusCode(401)->replyWithError("You don't have permission to edit the wiki");
		return $user;
	}

	/**
	 * Ensure the current country change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateCountry(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'id'              => ($request->method() == "POST") ? 'required|unique:countries,id|max:2|min:2|alpha' : 'required|exists:countries,id|max:2|min:2|alpha',
			'iso_a3'          => ($request->method() == "POST") ? 'required|unique:countries,iso_a3|max:3|min:3|alpha' : 'required|exists:countries,iso_a3|max:3|min:3|alpha',
			'fips'            => ($request->method() == "POST") ? 'required|unique:countries,fips|max:2|min:2|alpha' : 'required|exists:countries,fips|max:2|min:2|alpha',
			'continent'       => 'required|max:2|min:2|alpha',
			'name'            => 'required|max:191',
		]);

		if ($validator->fails()) {
			if($this->api)  return $this->setStatusCode(422)->replyWithError($validator->errors());
			if(!$this->api) return redirect('dashboard/countries/create')->withErrors($validator)->withInput();
		}

	}

}
