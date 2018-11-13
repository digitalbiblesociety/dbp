<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;

use App\Models\User\Key;
use App\Models\Country\JoshuaProject;
use App\Models\Country\Country;
use App\Transformers\CountryTransformer;
use Illuminate\View\View;

class CountriesController extends APIController
{

	/**
	 * Returns Countries
	 *
	 * @version 4
	 * @category v4_countries.all
	 * @link http://bible.build/countries - V4 Access
	 * @link https://api.dbp.test/countries?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 * @OA\Get(
	 *     path="/countries/",
	 *     tags={"Countries"},
	 *     summary="Returns Countries",
	 *     description="Returns the List of Countries",
	 *     operationId="v4_countries.all",
	 *     @OA\Parameter(name="l10n", in="query", description="When set to a valid three letter language iso, the returning results will be localized in the language matching that iso. (If an applicable translation exists).", @OA\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OA\Parameter(name="has_filesets", in="query", description="Filter the returned countries to only those containing filesets for languages spoken within the country", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="asset_id", in="query", description="Filter the returned countries to only those containing filesets for a specific asset id", @OA\Schema(ref="#/components/schemas/Asset/properties/id")),
	 *     @OA\Parameter(name="include_languages", in="query", description="When set to true, the return will include the major languages used in each country. You may optionally also include the names for those languages by setting it to `with_names`", @OA\Schema(type="string")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_countries.all")
	 *         )
	 *     )
	 * )
	 *
	 *
	 */
	public function index()
	{
		if (!$this->api) return view('wiki.countries.index');
		if (config('app.env') === 'local') ini_set('memory_limit', '864M');

		$has_filesets      = checkParam('has_filesets', null, 'optional') ?? true;
		$asset_id          = checkParam('bucket|bucket_id|asset_id', null, 'optional') ?? config('filesystems.disks.s3_fcbh.bucket');
		$include_languages = checkParam('include_languages', null, 'optional');

		$cache_string = 'countries' . $GLOBALS['i18n_iso'] . $has_filesets . $asset_id . $include_languages;
		if(config('app.debug')) \Cache::forget($cache_string);
		return \Cache::remember($cache_string, 1600, function () use ($has_filesets, $asset_id, $include_languages) {
				$countries = Country::with('currentTranslation')->when($has_filesets, function ($query) use ($asset_id) {
					$query->whereHas('languages.bibles.filesets', function ($query) use ($asset_id) {
						if($asset_id) $query->where('asset_id', $asset_id);
					});
				})->get();
				if ($include_languages !== null) {
					$countries->load([
						'languagesFiltered' => function ($query) use ($include_languages) {
							if ($include_languages === 'with_titles') {
								$query->with(['translation' => function ($query) {$query->where('language_translation_id', $GLOBALS['i18n_id']);}]);
							}
						},
					]);
				}

				return $this->reply(fractal()->collection($countries)->transformWith(new CountryTransformer()));
			});
	}

	/**
	 * Returns Joshua Project Country Information
	 *
	 * @version 4
	 * @category v4_countries.jsp
	 * @link http://bible.build/countries/joshua-project/ - V4 Access
	 * @link https://api.dbp.test/countries/joshua-project?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
	 *
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function joshuaProjectIndex()
	{
		if(config('app.env') === 'local') \Cache::forget('countries_jp_' . $GLOBALS['i18n_iso']);
		$joshua_project_countries = \Cache::remember('countries_jp_' . $GLOBALS['i18n_iso'], 1600, function () {
			$countries = JoshuaProject::with(['country',
				'translations' => function ($query) {
					$query->where('language_id', $GLOBALS['i18n_id']);
				},
			])->get();

			return fractal($countries,CountryTransformer::class);
		});
        return $this->reply($joshua_project_countries);
	}

	/**
	 * Returns the Specified Country
	 *
	 * @version 4
	 * @category v4_countries.one
	 * @link http://bible.build/countries/RU/ - V4 Access
	 * @link https://api.dbp.test/countries/ru?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_one - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/countries/{id}",
	 *     tags={"Countries"},
	 *     summary="Returns a single Country",
	 *     description="Returns a single Country",
	 *     operationId="v4_countries.one",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", description="The country ID", required=true, @OA\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OA\Parameter(name="communications", in="query", description="", @OA\Schema(ref="#/components/schemas/CountryCommunication")),
	 *     @OA\Parameter(name="economy", in="query", description="",        @OA\Schema(ref="#/components/schemas/CountryEconomy")),
	 *     @OA\Parameter(name="energy", in="query", description="",         @OA\Schema(ref="#/components/schemas/CountryEnergy")),
	 *     @OA\Parameter(name="geography", in="query", description="",      @OA\Schema(ref="#/components/schemas/CountryGeography")),
	 *     @OA\Parameter(name="government", in="query", description="",     @OA\Schema(ref="#/components/schemas/CountryGovernment")),
	 *     @OA\Parameter(name="government", in="query", description="",     @OA\Schema(ref="#/components/schemas/CountryGovernment")),
	 *     @OA\Parameter(name="issues", in="query", description="",         @OA\Schema(ref="#/components/schemas/CountryIssues")),
	 *     @OA\Parameter(name="people", in="query", description="",         @OA\Schema(ref="#/components/schemas/CountryPeople")),
	 *     @OA\Parameter(name="ethnicities", in="query", description="",    @OA\Schema(ref="#/components/schemas/CountryEthnicity")),
	 *     @OA\Parameter(name="regions", in="query", description="",        @OA\Schema(ref="#/components/schemas/CountryRegion")),
	 *     @OA\Parameter(name="religions", in="query", description="",      @OA\Schema(ref="#/components/schemas/CountryReligion")),
	 *     @OA\Parameter(name="transportation", in="query", description="", @OA\Schema(ref="#/components/schemas/CountryTransportation")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_countries.one")
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
		$cache_string = 'countries_'. $id . $GLOBALS['i18n_iso'];
		if(config('app.debug')) \Cache::forget($cache_string);
		$country = \Cache::remember($cache_string, 1600, function () use ($id) {
			$country = Country::with('languagesFiltered.bibles.translations')->find($id);
			if(!$country) return $this->setStatusCode(404)->replyWithError(trans('api.countries_errors_404', ['id' => $id], $GLOBALS['i18n_iso']));
			return $country;
		});
		if(!is_a($country, Country::class)) return $country;
		$includes = $this->loadWorldFacts($country);
		if($this->api) return $this->reply(fractal($country, new CountryTransformer())->serializeWith($this->serializer)->parseIncludes($includes));
		return view('wiki.countries.show', compact('country'));
	}

	/**
	 * Create a new Country
	 *
	 * @version 4
	 * @category ui_countries.create
	 * @link http://bible.build/countries/RU/ - V4 Access
	 * @link https://api.dbp.test/countries/ru?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_one - V4 Test Docs
	 *
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function create()
	{
		$this->validateUser();

		return view('wiki.countries.create');
	}

	/**
	 * Store a new Country
	 *
	 * @version 4
	 *
	 * @OA\Post(
	 *     path="/countries/",
	 *     tags={"Countries"},
	 *     summary="Create a new Country",
	 *     description="Create a new Country",
	 *     operationId="v4_countries.store",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Information supplied for Country creation", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(ref="#/components/schemas/Country")
	 *     )),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_countries.one")
	 *         )
	 *     )
	 * )
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	// TODO: Add create country route (Low priority)
	public function store()
	{
		return null;
	}

	/**
	 * Edit the Specified Country
	 *
	 * @param $id
	 *
	 * @return View
	 */
	public function edit($id)
	{
		$country = Country::find($id);

		return view('wiki.countries.edit', compact('country'));
	}


	/**
	 * Update the Specified Country
	 *
	 * @OA\Put(
	 *     path="/countries/{id}",
	 *     tags={"Countries"},
	 *     summary="Update a new Country",
	 *     description="Update a new Country",
	 *     operationId="v4_countries.update",
	 *     @OA\Parameter( name="id", in="path", description="The country ID", required=true, @OA\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_countries.one")
	 *         )
	 *     )
	 * )
	 *
	 * @param $id
	 *
	 * @return View
	 */
	public function update($id)
	{
		$this->validateUser();
		$this->validateCountry();

		$country = Country::find($id);
		if ($this->api) return $this->reply(trans('api.countries_update_200', []));

		return view('wiki.countries.show', compact('country'));
	}

	private function loadWorldFacts($country)
	{
		$loadedProfiles = [];
		// World Factbook
		$profiles['communications'] = checkParam('communications', null, 'optional') ?? 0;
		$profiles['economy']        = checkParam('economy', null, 'optional') ?? 0;
		$profiles['energy']         = checkParam('energy', null, 'optional') ?? 0;
		$profiles['geography']      = checkParam('geography', null, 'optional') ?? 0;
		$profiles['government']     = checkParam('government', null, 'optional') ?? 0;
		$profiles['issues']         = checkParam('issues', null, 'optional') ?? 0;
		$profiles['people']         = checkParam('people', null, 'optional') ?? 0;
		$profiles['ethnicities']    = checkParam('ethnicity', null, 'optional') ?? 0;
		$profiles['regions']        = checkParam('regions', null, 'optional') ?? 0;
		$profiles['religions']      = checkParam('religions', null, 'optional') ?? 0;
		$profiles['transportation'] = checkParam('transportation', null, 'optional') ?? 0;
		$profiles['joshuaProject']  = checkParam('joshuaProject', null, 'optional') ?? 0;
		foreach ($profiles as $key => $profile) {
			if ($profile !== 0) {
				$country->load($key);
				if($country->{$key} !== null) $loadedProfiles[] = $key;
			}
		}
		return $loadedProfiles;
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
		if (!$this->api) $user = \Auth::user();
		if (!$user) {
			$key = Key::where('key', $this->key)->first();
			if(!$key) return $this->setStatusCode(403)->replyWithError(trans('api.auth_key_validation_failed'));
			$user = $key->user;
		}
		if (!$user->archivist && !$user->admin) return $this->setStatusCode(401)->replyWithError(trans('api.auth_wiki_validation_failed'));

		return $user;
	}

	/**
	 * Ensure the current country change is valid
	 *
	 * @return mixed
	 */
	private function validateCountry()
	{
		$validator = \Validator::make(request()->all(), [
			'id'        => (request()->method() === 'POST') ? 'required|unique:dbp.countries,id|max:2|min:2|alpha' : 'required|exists:dbp.countries,id|max:2|min:2|alpha',
			'iso_a3'    => (request()->method() === 'POST') ? 'required|unique:dbp.countries,iso_a3|max:3|min:3|alpha' : 'required|exists:dbp.countries,iso_a3|max:3|min:3|alpha',
			'fips'      => (request()->method() === 'POST') ? 'required|unique:dbp.countries,fips|max:2|min:2|alpha' : 'required|exists:dbp.countries,fips|max:2|min:2|alpha',
			'continent' => 'required|max:2|min:2|alpha',
			'name'      => 'required|max:191',
		]);

		if ($validator->fails()) {
			if ($this->api) return $this->setStatusCode(422)->replyWithError($validator->errors());
			if (!$this->api) return redirect('dashboard/countries/create')->withErrors($validator)->withInput();
		}

		return null;
	}

}
