<?php

namespace App\Http\Controllers;

use App\Models\Country\JoshuaProject;
use App\Models\Language\Language;
use App\Models\Country\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Spatie\Fractalistic\ArraySerializer;

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
	 *     tags={"Countries"},
	 *     summary="Returns Countries",
	 *     description="Returns the List of Countries",
	 *     operationId="v4_countries.all",
	 *     @OAS\Parameter(name="l10n", in="query", description="When set to a valid three letter language iso, the returning results will be localized in the language matching that iso. (If an applicable translation exists).", @OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OAS\Parameter(name="has_filesets", in="query", description="Filter the returned countries to only those containing filesets for languages spoken within the country", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="bucket_id", in="query", description="Filter the returned countries to only those containing filesets for a specific bucket", @OAS\Schema(ref="#/components/schemas/Bucket/properties/id")),
	 *     @OAS\Parameter(name="include_languages", in="query", description="When set to true, the return will include the major languages used in each country. You may optionally also include the names for those languages by setting it to `with_names`", @OAS\Schema(type="string")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/v4_countries.all")
	 *         )
	 *     )
	 * )
	 *
	 *
	 */
	public function index()
	{
		if (!$this->api) {
			return view('wiki.countries.index');
		}
		if (env('APP_ENV') == 'local') {
			ini_set('memory_limit', '864M');
		}

		$l10n              = checkParam('l10n', null, 'optional') ?? "eng";
		$has_filesets      = checkParam('has_filesets', null, 'optional') ?? true;
		$bucket_id         = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$include_languages = checkParam('include_languages', null, 'optional');

		\Cache::forget("countries" . $l10n . $has_filesets . $bucket_id . $include_languages . \i18n::getCurrentLocale());

		return \Cache::remember("countries" . $l10n . $has_filesets . $bucket_id . $include_languages . \i18n::getCurrentLocale(),
			1600, function () use ($l10n, $has_filesets, $bucket_id, $include_languages) {
				if ($l10n) {
					$language = Language::where('iso', $l10n)->first();
					if (!$language) {
						return $this->setStatusCode(404)->replyWithError(trans('api.language_errors_404'));
					}
				}
				$countries = Country::exclude('introduction')->
				when($has_filesets, function ($query) use ($bucket_id) {
					$query->whereHas('languages.bibles.filesets', function ($query) use ($bucket_id) {
						if ($bucket_id) {
							$query->where('bucket_id', $bucket_id);
						}
					});
				})->get();
				if ($l10n != "eng") {
					$countries->load([
						'translation' => function ($query) use ($language) {
							$query->where('language_id', $language->id);
						},
					]);
				}
				if (isset($include_languages)) {
					$countries->load([
						'languagesFiltered' => function ($query) use ($language, $include_languages) {
							if ($include_languages == "with_titles") {
								$query->with([
									'translation' => function ($query) use ($language) {
										$query->where('language_translation', $language->id);
									},
								]);
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
	 * @link https://api.dbp.dev/countries/joshua-project?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
	 *
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function joshuaProjectIndex()
	{
		$l10n = (isset($_GET['iso'])) ? $_GET['iso'] : 'eng';
		\Cache::forget("countries_jp_" . $l10n);
		$joshua_project_countries = \Cache::remember("countries_jp_" . $l10n, 1600, function () use ($l10n) {
			$language  = Language::where('iso', $l10n)->first();
			$countries = JoshuaProject::with([
				'translations' => function ($query) use ($language) {
					$query->where('language_id', $language->id);
				},
			],'country')->get();

			return fractal()->collection($countries)->transformWith(CountryTransformer::class);
		});
        return $this->reply($joshua_project_countries);
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
	 *     tags={"Countries"},
	 *     summary="Returns a single Country",
	 *     description="Returns a single Country",
	 *     operationId="v4_countries.one",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="id", in="path", description="The country ID", required=true, @OAS\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OAS\Parameter(name="communications", in="query", description="", @OAS\Schema(ref="#/components/schemas/CountryCommunication")),
	 *     @OAS\Parameter(name="economy", in="query", description="",        @OAS\Schema(ref="#/components/schemas/CountryEconomy")),
	 *     @OAS\Parameter(name="energy", in="query", description="",         @OAS\Schema(ref="#/components/schemas/CountryEnergy")),
	 *     @OAS\Parameter(name="geography", in="query", description="",      @OAS\Schema(ref="#/components/schemas/CountryGeography")),
	 *     @OAS\Parameter(name="government", in="query", description="",     @OAS\Schema(ref="#/components/schemas/CountryGovernment")),
	 *     @OAS\Parameter(name="government", in="query", description="",     @OAS\Schema(ref="#/components/schemas/CountryGovernment")),
	 *     @OAS\Parameter(name="issues", in="query", description="",         @OAS\Schema(ref="#/components/schemas/CountryIssues")),
	 *     @OAS\Parameter(name="people", in="query", description="",         @OAS\Schema(ref="#/components/schemas/CountryPeople")),
	 *     @OAS\Parameter(name="ethnicities", in="query", description="",    @OAS\Schema(ref="#/components/schemas/CountryEthnicity")),
	 *     @OAS\Parameter(name="regions", in="query", description="",        @OAS\Schema(ref="#/components/schemas/CountryRegion")),
	 *     @OAS\Parameter(name="religions", in="query", description="",      @OAS\Schema(ref="#/components/schemas/CountryReligion")),
	 *     @OAS\Parameter(name="transportation", in="query", description="", @OAS\Schema(ref="#/components/schemas/CountryTransportation")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/v4_countries.one")
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
		$country  = Country::with('languagesFiltered.bibles.currentTranslation', 'geography','maps')->find($id);
		$includes = $this->loadWorldFacts($country);
		if (!$country) return $this->setStatusCode(404)->replyWithError(trans('api.countries_errors_404', ['l10n' => $id]));

		if ($this->api) return $this->reply(fractal()->item($country)->transformWith(new CountryTransformer())->serializeWith(ArraySerializer::class)->parseIncludes($includes)->ToArray());
		return view('wiki.countries.show', compact('country'));
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

		return view('wiki.countries.create');
	}

	/**
	 * Store a new Country
	 *
	 * @version 4
	 *
	 * @OAS\Post(
	 *     path="/countries/",
	 *     tags={"Countries"},
	 *     summary="Create a new Country",
	 *     description="Create a new Country",
	 *     operationId="v4_countries.store",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\RequestBody(required=true, description="Information supplied for Country creation", @OAS\MediaType(mediaType="application/json",
	 *          @OAS\Schema(ref="#/components/schemas/Country")
	 *     )),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/v4_countries.one")
	 *         )
	 *     )
	 * )
	 *
	 * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
	 *
	 *
	 *
	 */
	// TODO: Add create country route (Low priority)
	public function store(Request $request)
	{
		$validator = $request->validate([
			'id'           => 'string|max:2|min:2|required',
			'iso_a3'       => 'string|max:3|min:3|required',
			'fips'         => 'string|max:2|min:2|required',
			'continent'    => 'string|max:2|min:2|required',
			'name'         => 'string|max:191|required',
			'introduction' => 'string|min:6|nullable',
		]);
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
	 * @OAS\Put(
	 *     path="/countries/{id}",
	 *     tags={"Countries"},
	 *     summary="Update a new Country",
	 *     description="Update a new Country",
	 *     operationId="v4_countries.update",
	 *     @OAS\Parameter( name="id", in="path", description="The country ID", required=true, @OAS\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/v4_countries.one")
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
		$this->validateCountry(request());

		$country = Country::find($id);

		if ($this->api) {
			return $this->reply(trans('api.countries_update_200', []));
		}

		return view('wiki.countries.show', compact('country'));
	}

	private function loadWorldFacts($country)
	{
		$loadedProfiles = [];
		// World Factbook
		$profiles['communications'] = checkParam('communications', null, 'optional');
		$profiles['economy']        = checkParam('economy', null, 'optional');
		$profiles['energy']         = checkParam('energy', null, 'optional');
		$profiles['geography']      = checkParam('geography', null, 'optional');
		$profiles['government']     = checkParam('government', null, 'optional');
		$profiles['government']     = checkParam('government', null, 'optional');
		$profiles['issues']         = checkParam('issues', null, 'optional');
		$profiles['people']         = checkParam('people', null, 'optional');
		$profiles['ethnicities']    = checkParam('ethnicity', null, 'optional');
		$profiles['regions']        = checkParam('regions', null, 'optional');
		$profiles['religions']      = checkParam('religions', null, 'optional');
		$profiles['transportation'] = checkParam('transportation', null, 'optional');
		foreach ($profiles as $key => $profile) {
			if ($profile != null) {
				$country->load($key);
				$loadedProfiles[] = $key;
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
		if (!$this->api) {
			$user = Auth::user();
		}
		if (!$user) {
			$key = Key::where('key', $this->key)->first();
			if (!isset($key)) {
				return $this->setStatusCode(403)->replyWithError(trans('api.auth_key_validation_failed'));
			}
			$user = $key->user;
		}
		if (!$user->archivist AND !$user->admin) {
			return $this->setStatusCode(401)->replyWithError(trans('api.auth_wiki_validation_failed'));
		}

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
		$validator = Validator::make($request->all(), [
			'id'        => ($request->method() == "POST") ? 'required|unique:countries,id|max:2|min:2|alpha' : 'required|exists:countries,id|max:2|min:2|alpha',
			'iso_a3'    => ($request->method() == "POST") ? 'required|unique:countries,iso_a3|max:3|min:3|alpha' : 'required|exists:countries,iso_a3|max:3|min:3|alpha',
			'fips'      => ($request->method() == "POST") ? 'required|unique:countries,fips|max:2|min:2|alpha' : 'required|exists:countries,fips|max:2|min:2|alpha',
			'continent' => 'required|max:2|min:2|alpha',
			'name'      => 'required|max:191',
		]);

		if ($validator->fails()) {
			if ($this->api) {
				return $this->setStatusCode(422)->replyWithError($validator->errors());
			}
			if (!$this->api) {
				return redirect('dashboard/countries/create')->withErrors($validator)->withInput();
			}
		}

	}

}
