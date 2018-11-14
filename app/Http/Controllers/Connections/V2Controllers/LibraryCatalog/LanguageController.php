<?php
namespace App\Http\Controllers\Connections\V2Controllers\LibraryCatalog;

use App\Http\Controllers\APIController;
use App\Models\Language\Language;
use App\Models\User\User;
use App\Traits\AccessControlAPI;
use App\Transformers\V2\LibraryCatalog\LanguageListingTransformer;
use Illuminate\Http\Request;


class LanguageController extends APIController
{

	use AccessControlAPI;

	/**
	 * @OA\Get(
	 *     path="/library/language/",
	 *     tags={"Library Catalog"},
	 *     summary="Returns the list of languages",
	 *     description="Returns the List of Languages",
	 *     operationId="v2_library_language",
	 *     @OA\Parameter(in="query",name="code",description="Get the entry for a three letter language code",@OA\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OA\Parameter(in="query",name="name",description="Get the entry for a part of a language name in either native language or English",@OA\Schema(type="string",example="Spanish")),
	 *     @OA\Parameter(in="query",name="full_word",description="Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string new is anywhere in the language name, like in Newari and Awa for Papua New Guinea. When true, it will only return volumes where the language name contains the full word 'new', like in `Awa for Papua New Guinea`. Default is false",@OA\Schema(type="boolean",default=false,example=false)),
	 *     @OA\Parameter(in="query",deprecated=true,name="family_only",description="When set to true the returned list is of only legal language families. The default is false",@OA\Schema(type="boolean")),
	 *     @OA\Parameter(in="query",deprecated=true,name="possibilities",description="When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria",@OA\Schema(type="boolean",default=true,example=true)),
	 *     @OA\Parameter(in="query",name="sort_by",description="Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'",@OA\Schema(ref="#/components/schemas/Asset/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_language"))
	 *     )
	 * )
	 *
	 */
	public function languageListing()
	{
		// Params
		$code                  = checkParam('code', null, 'optional');
		$name                  = checkParam('name', null, 'optional');
		$full_word             = checkParam('full_word', null, 'optional') ?? 'false';
		$sort_by               = checkParam('sort_by', null, 'optional') ?? 'name';

		// Caching Logic
		$cache_string = 'v' . $this->v . '_languages_' . $code.$full_word.$name.$sort_by;
		if(config('app.env') === 'local') \Cache::forget($cache_string);
		$cached_languages = \Cache::remember($cache_string, 1600, function () use ($code, $full_word, $name, $sort_by) {
			$languages = Language::select(['id', 'iso2B', 'iso', 'name'])->orderBy($sort_by)
				->when($code, function ($query) use ($code) {
					return $query->where('iso', $code);
				})
				->has('filesets')
				// Filter results by language name when set
				->when($name, function ($query) use ($name, $full_word) {
					return $query->whereHas('translations', function ($query) use ($name, $full_word) {
						$added_space = ($full_word === 'true') ? ' ': '';
						$query->where('name', 'like', '%' . $name . $added_space . '%')->orWhere('name', $name);
					});
				})->get();
			return fractal($languages,new LanguageListingTransformer())->serializeWith($this->serializer);
		});

		return $this->reply($cached_languages);
	}

	/**
	 *
	 * _method=put : REQUIRED for PUT DBT methods - PUT is not properly supported. To effect DBT methods requiring PUT, use the GET HTTP method and &_method=put.
	 * iso_code: The three letter ISO language code.
	 * glotto_code: the Glottolog code
	 * name: The native language language name.
	 * english_name: The English language language name.
	 * variant (optional): [true|false] Forces the language code creation to be a variant of the ISO code and not the ISO code even if it is available as a DBP language code. This is used when FCBH considers the language being defined to be a variant of an official ISO language.
	 * family_code (optional): The language code of the family to which this language belongs. If left empty or a non-existent language code is entered, the family_code will be set the same as the code entered to create this language.
	 *
	 * @param Request $request
	 * @return User|mixed|null
	 */
	public function languageCreate(Request $request)
	{
		$user = $this->validateUser();
		if(!is_a($user,new User())) return $user;
		$this->validateLanguage($request);

		Language::create($request->all());
		return $this->reply(['status' => 'Success']);
	}


	/**
	 * Handle the Country Lang route for V2
	 *
	 * // TODO: backwards compatibility - low priority
	 * // TODO: Generation code for img_type & img_size
	 *
	 * @OA\Get(
	 *     path="/country/countrylang/",
	 *     tags={"Country Language"},
	 *     summary="Returns Languages and the countries associated with them",
	 *     description="Filter languages by a specified country code or filter countries by specified language code. Country flags can also be retrieved by requesting one of the permitted image sizes. Languages can be sorted by the country code (default) and the language code.",
	 *     operationId="v2_country_lang",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="lang_code",in="query",description="Get records by ISO language code", @OA\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OA\Parameter(name="country_code",in="query",description="Get records by ISO country code", @OA\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OA\Parameter(name="additional",in="query",description="Get colon separated list of optional countries", @OA\Schema(type="integer",enum={0,1},default=0)),
	 *     @OA\Parameter(name="sort_by",in="query",description="Sort by lang_code or country_code", @OA\Schema(type="string",enum={"country_code","lang_code"},default="country_code")),
	 *     @OA\Parameter(name="img_type",in="query",description="Includes a country flag image of the specified file type", @OA\Schema(type="string",enum={"png","svg"},default="png")),
	 *     @OA\Parameter(name="img_size",in="query",description="Include country flag in entries in requested size. Note: This parameter accepts any resolution in the format (width)x(height), however, selecting a resolution with an aspect ratio other than 1:1 or 4:3 will likely result in distortion. We encourage you to use a standard size (40x30, 80x60, 160X120, 320X240, 640X480, or 1280X960) because they can be generated much more quickly than other sizes. If this parameter is provided and img_type is omitted, img_type is assumed to be png. This parameter is ignored when img_type is svg.",@OA\Schema(type="string",example="160X120")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_country_lang"))
	 *     )
	 * )
	 *
	 *
	 * @return View|JSON
	 */
	public function CountryLang()
	{
		// If it's not an API route send them to the documentation
		if (!$this->api) {
			return view('docs.v2.country_language');
		}

		if (config('app.env') == 'local') {
			ini_set('memory_limit', '864M');
		}

		// Get and set variables from Params. Both are optional.
		$sort_by            = checkParam('sort_by', null, 'optional');
		$lang_code          = checkParam('lang_code', null, 'optional');
		$country_code       = checkParam('country_code', null, 'optional');
		$country_additional = checkParam('additional', null, 'optional');
		$cache_string       = 'v2_country_lang_' . $sort_by . $lang_code . $country_code . $country_additional;

		$countryLang = \Cache::remember($cache_string, 1600,
			function () use ($sort_by, $lang_code, $country_code, $country_additional) {

				// Fetch Languages and add conditional sorting / loading depending on params
				$languages = Language::has('primaryCountry')->has('bibles.filesets')->with('primaryCountry', 'countries')
					->when($sort_by, function ($q) use ($sort_by) {
					    return $q->orderBy($sort_by, 'desc');
					})->when($lang_code, function ($q) use ($lang_code) {
						return $q->where('iso', $lang_code);
					})->when($country_code, function ($q) use ($country_code) {
						return $q->where('country_id', $country_code);
					})->get();
				if ($country_additional) $languages->load('countries');

				return fractal($languages, new LanguageListingTransformer())->serializeWith($this->serializer);
			});
		// Transform and return JSON
		return $this->reply($countryLang);
	}

	/**
	 * API V2:
	 * Returns a List of Languages that contain resources and if the
	 * language is a dialect, returns the parent language as well.
	 *
	 * @param root (optional): the native language or English language language name root. Can be used to restrict the response to only languages that start with 'Quechua' for example
	 * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param language_code (optional): the three letter language code.
	 * @param media (optional): [text|audio|video] - the format of languages the caller is interested in. This specifies if you want languages available in text or languages available in audio.
	 *
	 * @deprecated delivery (optional): [streaming|web_streaming|download|download_text|mobile|sign_language|local_bundled|podcast|mp3_cd|digital_download|bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming|mobile". 'any' means any of the supported methods (this list may change over time). 'none' means assets that are not approved for any of the supported methods. All returned by default.
	 *
	 * @param status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @param resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 * @param organization_id : The id of an organization by which to filter the languages of available volumes.
	 *
	 * @OA\Get(
	 *     path="/library/volumelanguage/",
	 *     tags={"Library Catalog"},
	 *     summary="Returns the list of languages",
	 *     description="This method retrieves the list of languages for available volumes and the related volume data in the system according to the filter specified.",
	 *     operationId="v2_library_volumeLanguageFamily",
	 *     @OA\Parameter(name="language_code",in="query"),
	 *     @OA\Parameter(name="root",in="query"),
	 *     @OA\Parameter(name="media",in="query"),
	 *     @OA\Parameter(name="delivery",in="query"),
	 *     @OA\Parameter(name="full_word",in="query"),
	 *     @OA\Parameter(name="status",in="query"),
	 *     @OA\Parameter(name="resolution",in="query"),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_language"))
	 *     )
	 * )
	 *
	 * @return View|JSON
	 */
	public function volumeLanguage()
	{
		// $delivery =  checkParam('delivery', null, 'optional');
		$iso             = checkParam('language_code', null, 'optional');
		$root            = checkParam('root', null, 'optional');
		$media           = checkParam('media', null, 'optional');
		$organization_id = checkParam('organization_id', null, 'optional');

		if(config('app.env') === 'local') \Cache::forget('volumeLanguage' . $root . $iso . $media . $organization_id);
		$languages = \Cache::remember('volumeLanguage' . $root . $iso . $media . $organization_id, 2400,
			function () use ($root, $iso, $media, $organization_id) {
				$languages = Language::select(['id', 'iso', 'iso2B', 'iso2T', 'iso1', 'name', 'autonym'])->with('parent')
					->has('filesets')
					->when($iso, function ($query) use ($iso) {
						return $query->where('iso', $iso);
					})->when($root, function ($query) use ($root) {
						return $query->where('name', '%' . $root . '%');
					})->when($organization_id, function ($query) use ($organization_id) {
						return $query->whereHas('filesets', function ($q) use ($organization_id) {
							$q->where('organization_id', $organization_id);
						});
					})->when($media, function ($query) use ($media) {
						switch ($media) {
							case "audio": {
								return $query->has('bibles.filesetAudio');
								break;
							}
							case "video": {
								return $query->has('bibles.filesetFilm');
								break;
							}
							case "text": {
								return $query->has('bibles.filesets');
								break;
							}
						}
					})->get();
				return fractal($languages, new LanguageListingTransformer())->serializeWith($this->serializer)->toArray();
			});
		return $this->reply($languages);
	}


	/**
	 * API V2:
	 * Returns of List of Macro-Languages that contain resources and their dialects
	 *
	 * @param language_code (optional): the three letter language code.
	 * @param root (optional): the native language or English language language name root. Can be used to restrict the response to only languages that start with 'Quechua' for example
	 * @param media (optional): [text|audio|video] - the format of languages the caller is interested in. This specifies if you want languages available in text or languages available in audio.
	 * @param delivery (optional): [streaming|web_streaming|download|download_text|mobile|sign_language|local_bundled|podcast|mp3_cd|digital_download|bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming|mobile". 'any' means any of the supported methods (this list may change over time). 'none' means assets that are not approved for any of the supported methods. All returned by default.
	 *
	 * @deprecated full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @deprecated status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @deprecated resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 *
	 * @param organization_id : The id of an organization by which to filter the languages of available volumes.
	 *
	 *
	 * @OA\Get(
	 *     path="/library/volumelanguagefamily/",
	 *     tags={"Library Catalog"},
	 *     summary="Returns the list of languages",
	 *     description="This method retrieves the list of language families for available volumes and the related volume data in the system according to the filter specified.",
	 *     operationId="v2_library_volumeLanguageFamily",
	 *     @OA\Parameter(name="language_code",in="query"),
	 *     @OA\Parameter(name="root",in="query"),
	 *     @OA\Parameter(name="media",in="query"),
	 *     @OA\Parameter(name="delivery",in="query"),
	 *     @OA\Parameter(name="full_word",in="query"),
	 *     @OA\Parameter(name="status",in="query"),
	 *     @OA\Parameter(name="resolution",in="query"),
	 *     @OA\Parameter(ref="#/components/parameters/l10n"),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_language"))
	 *     )
	 * )
	 *
	 * @return mixed
	 */
	public function volumeLanguageFamily()
	{
		$iso             = checkParam('language_code', null, 'optional');
		$root            = checkParam('root', null, 'optional');
		$media           = checkParam('media', null, 'optional');
		$delivery        = checkParam('delivery', null, 'optional');
		$organization_id = checkParam('organization_id', null, 'optional');

		$access_control = $this->accessControl($this->key, 'api');

		if(config('app.env') === 'local') \Cache::forget('volumeLanguageFamily' . $root . $iso . $media . $delivery . $organization_id);
		$languages = \Cache::remember('volumeLanguageFamily' . $root . $iso . $media . $delivery . $organization_id, 2400, function () use ($root, $iso, $access_control, $media, $delivery, $organization_id) {
				$languages = Language::with('bibles')->with('dialects')
					->whereHas('filesets', function ($query) use($access_control) {
						$query->whereIn('hash_id', $access_control->hashes);
					})
					->with(['dialects.childLanguage' => function ($query) {
					    $query->select(['id', 'iso']);
					}])
					->when($iso, function ($query) use ($iso) {
					    return $query->where('iso', $iso);
					})->when($root, function ($query) use ($root) {
						return $query->where('name', 'LIKE', '%' . $root . '%');
					})->when($root, function ($query) use ($root) {
						return $query->where('name', 'LIKE', '%' . $root . '%');
					})->get();
				return fractal($languages, new LanguageListingTransformer())->serializeWith($this->serializer);
		});
		return $this->reply($languages);
	}

	/**
	 * Ensure the current User has permissions to alter the alphabets
	 *
	 * @return \App\Models\User\User|mixed|null
	 */
	private function validateUser()
	{
		$user = Auth::user();
		if (!$user) {
			$key = Key::where('key', $this->key)->first();
			if (!isset($key)) return $this->setStatusCode(403)->replyWithError('No Authentication Provided or invalid Key');
			$user = $key->user;
		}
		if (!$user->archivist AND !$user->admin) return $this->setStatusCode(401)->replyWithError("You don't have permission to edit the wiki");

		return $user;
	}

	public function validateLanguage(Request $request)
	{
		$latLongRegex = '^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$';
		$validator    = Validator::make($request->all(), [
			'glotto_id'  => ($request->method() == "POST") ? 'alpha_num|unique:dbp.languages,glotto_id|max:8|required_if:iso,null|nullable' : 'alpha_num|exists:dbp.languages,glotto_id|max:8|required_if:iso,null|nullable',
			'iso'        => ($request->method() == "POST") ? 'alpha|unique:dbp.languages,iso|max:3|required_if:glotto_code,null|nullable' : 'alpha|exists:dbp.languages,iso|max:3|required_if:glotto_code,null|nullable',
			'iso2B'      => ($request->method() == "POST") ? 'alpha|max:3|unique:dbp.languages,iso2B' : 'alpha|max:3',
			'iso2T'      => ($request->method() == "POST") ? 'alpha|max:3|unique:dbp.languages,iso2T' : 'alpha|max:3',
			'iso1'       => ($request->method() == "POST") ? 'alpha|max:2|unique:dbp.languages,iso1' : 'alpha|max:2',
			'name'       => 'required|string|max:191',
			'autonym'    => 'required|string|max:191',
			'level'      => 'string|max:191|nullable',
			'maps'       => 'string|max:191|nullable',
			'population' => 'integer',
			'latitude'   => 'regex:' . $latLongRegex,
			'longitude'  => 'regex:' . $latLongRegex,
			'country_id' => 'alpha|max:2|exists:dbp.countries,id',
		]);

		if ($validator->fails()) {
			if ($this->api) return $this->setStatusCode(422)->replyWithError($validator->errors());
			if (!$this->api) return redirect('dashboard/alphabets/create')->withErrors($validator)->withInput();
		}

	}

}