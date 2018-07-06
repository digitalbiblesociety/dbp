<?php

namespace App\Http\Controllers;

use App\Models\Language\Language;
use App\Models\Language\LanguageTranslation;
use App\Transformers\LanguageTransformer;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Traits\AccessControlAPI;

class LanguagesController extends APIController
{

	use AccessControlAPI;

	/**
	 * Display a listing of the resource.
	 * Fetches the records from the database > passes them through fractal for transforming.
	 *
	 * @param code (optional): Get the entry for a three letter language code.
	 * @param name (optional): Get the entry for a part of a language name in either native language or English.
	 * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
	 *
	 * @deprecated family_only (optional): [true|false] When set to true the returned list is of only legal language families. The default is false.
	 * @deprecated possibilities (optional); [true|false] When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria.
	 *
	 * @link https://api.dbp.dev/languages?key=1234&v=4&pretty
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @OAS\Get(
	 *     path="/languages/",
	 *     tags={"Languages"},
	 *     summary="Returns Languages",
	 *     description="Returns the List of Languages",
	 *     operationId="v4_languages.all",
	 *     @OAS\Parameter(name="country",in="query",description="The country",@OAS\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OAS\Parameter(name="iso",in="query",description="The iso code to filter languages by",@OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OAS\Parameter(name="language_name",in="query",description="The language_name field will filter results by a specific language name",@OAS\Schema(type="object")),
	 *     @OAS\Parameter(name="sort_by",in="query",description="The sort_by field will order results by a specific field",@OAS\Schema(type="object")),
	 *     @OAS\Parameter(name="has_bibles",in="query",description="When set to true will filter language results depending whether or not they have bibles.",@OAS\Schema(type="object")),
	 *     @OAS\Parameter(name="has_filesets",in="query",description="When set to true will filter language results depending whether or not they have filesets. Will add new filesets_count field to the return.",@OAS\Schema(type="object",default=null,example=true)),
	 *     @OAS\Parameter(name="bucket_id",in="query",description="The bucket_id",@OAS\Schema(ref="#/components/schemas/Bucket/properties/id")),
	 *     @OAS\Parameter(name="include_alt_names",in="query",description="The include_alt_names",@OAS\Schema(ref="#/components/schemas/Language/properties/name")),
	 *     @OAS\Parameter(ref="#/components/parameters/l10n"),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/Language"))
	 *     )
	 * )
	 *
	 * @OAS\Get(
	 *     path="/library/language/",
	 *     tags={"Library Catalog"},
	 *     summary="Returns the list of languages",
	 *     description="Returns the List of Languages",
	 *     operationId="v2_library_language",
	 *     @OAS\Parameter(name="code",in="query",description="Get the entry for a three letter language code",@OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OAS\Parameter(name="name",in="query",description="Get the entry for a part of a language name in either native language or English",@OAS\Schema(type="object")),
	 *     @OAS\Parameter(name="full_word",in="query",description="Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in `Newari` and `Awa for Papua New Guinea`. When true, it will only return volumes where the language name contains the full word 'new', like in `Awa for Papua New Guinea`. Default is false",@OAS\Schema(type="object")),
	 *     @OAS\Parameter(name="family_only",in="query",description="When set to true the returned list is of only legal language families. The default is false",@OAS\Schema(type="object")),
	 *     @OAS\Parameter(name="possibilities",in="query",description="When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria",@OAS\Schema(type="object",default=null,example=true)),
	 *     @OAS\Parameter(name="sort_by",in="query",description="Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'",@OAS\Schema(ref="#/components/schemas/Bucket/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/l10n"),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_library_language"))
	 *     )
	 * )
	 *
	 */
	public function index()
	{
		if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');
		if (!$this->api) return view('wiki.languages.index');

		$country       = checkParam('country', null, 'optional');
		$code          = checkParam('code|iso', null, 'optional');
		$l10n          = checkParam('l10n', null, 'optional') ?? "eng";
		$l10n_language = Language::where('iso', $l10n)->first();

		$autonym = checkParam('autonym', null, 'optional');

		$language_name_portion = checkParam('name|language_name', null, 'optional');
		$full_word             = checkParam('full_word', null, 'optional');
		$family_only           = checkParam('family_only', null, 'optional');
		$possibilities         = checkParam('possibilities', null, 'optional');
		$sort_by               = checkParam('sort_by', null, 'optional') ?? "name";
		$has_bibles            = checkParam('has_bibles', null, 'optional');
		$has_filesets          = checkParam('has_filesets', null, 'optional');
		$bucket_id             = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$include_alt_names     = checkParam('include_alt_names', null, 'optional');
		$hide_restricted       = checkParam('hide_restricted', null, 'optional') ?? true;

		$access_control = $this->accessControl($this->key, "api");

		$cache_string = 'v' . $this->v . '_languages_' . $country . $code . $l10n . $l10n_language . $language_name_portion . $full_word . $family_only . $possibilities . $sort_by . $has_bibles . $has_filesets . $bucket_id . $include_alt_names;
		\Cache::forget($cache_string);
		$languages = \Cache::remember($cache_string, 1600, function () use (
			$country,
			$code,
			$l10n,
			$l10n_language,
			$language_name_portion,
			$full_word,
			$family_only,
			$possibilities,
			$sort_by,
			$has_bibles,
			$has_filesets,
			$bucket_id,
			$include_alt_names,
			$hide_restricted,
			$access_control
		) {
			$languages = Language::select(['id', 'iso2B', 'iso', 'name'])
			->when($has_bibles, function ($query) use ($has_bibles) {
			    return $query->has('bibles');
			})
			->when($has_filesets, function ($q) use ($bucket_id,$access_control,$hide_restricted) {
			        $q->whereHas('bibles.filesets', function ($query) use ($bucket_id,$access_control,$hide_restricted) {
			            if($bucket_id) $query->where('bucket_id', $bucket_id);
				        if($hide_restricted) $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
			        })->with(['bibles.filesets' => function ($query) use ($access_control, $hide_restricted) {
						if($hide_restricted) $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
					}]);
				}, // if has_filesets is set to false
			    function ($q) { $q->withCount('bibles');
			})->when($country, function ($query) use ($country) {
				return $query->whereHas('countries', function ($query) use ($country) {
					$query->where('country_id', $country);
				});
			})->when($code, function ($query) use ($code) {
				return $query->where('iso', $code);
			})->when($include_alt_names, function ($query) use ($has_bibles) {
				return $query->with('translations.translation_iso');
			})->when($language_name_portion, function ($query) use ($language_name_portion) {
				return $query->whereHas('translations', function ($query) use ($language_name_portion) {
					$query->where('name', $language_name_portion);
				})->orWhere('name', $language_name_portion);
			})->when($sort_by, function ($query) use ($sort_by) {
				return $query->orderBy($sort_by);
			})->get();

			if ($l10n) {
				if (!$include_alt_names) {
					$languages->load([
						'translation' => function ($query) use ($l10n_language) {
							$query->where('language_translation_id', $l10n_language->id);
						},
					]);
				}
			}

			if ($has_filesets AND $hide_restricted) {
				foreach ($languages as $key => $language) {
					foreach ($language->bibles as $bible_key => $bible) {
						if ($bible->filesets->count() == 0) {
							unset($languages[$key]->bibles[$bible_key]);
						}
					}
				}
			}

			return fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray();
		});
		return $this->reply($languages);
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
	 * @return View|JSON
	 */
	public function volumeLanguage()
	{
		if (env('APP_ENV') == 'local') {
			ini_set('memory_limit', '464M');
		}
		// $delivery =  checkParam('delivery', null, 'optional');
		$iso             = checkParam('language_code', null, 'optional');
		$root            = checkParam('root', null, 'optional');
		$media           = checkParam('media', null, 'optional');
		$organization_id = checkParam('organization_id', null, 'optional');

		$languages = \Cache::remember('volumeLanguage' . $root . $iso . $media . $organization_id, 2400,
			function () use ($root, $iso, $media, $organization_id) {
				return Language::select(['id', 'iso', 'iso2B', 'iso2T', 'iso1', 'name', 'autonym'])->with('parent')
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
			});
		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
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
	 * @OAS\Get(
	 *     path="/library/volumelanguagefamily/",
	 *     tags={"Library Catalog"},
	 *     summary="Returns the list of languages",
	 *     description="This method retrieves the list of language families for available volumes and the related volume data in the system according to the filter specified.",
	 *     operationId="v2_library_volumeLanguageFamily",
	 *     @OAS\Parameter(name="language_code",in="query"),
	 *     @OAS\Parameter(name="root",in="query"),
	 *     @OAS\Parameter(name="media",in="query"),
	 *     @OAS\Parameter(name="delivery",in="query"),
	 *     @OAS\Parameter(name="full_word",in="query"),
	 *     @OAS\Parameter(name="status",in="query"),
	 *     @OAS\Parameter(name="resolution",in="query"),
	 *     @OAS\Parameter(ref="#/components/parameters/l10n"),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_library_language"))
	 *     )
	 * )
	 *
	 * @return mixed
	 */
	public function volumeLanguageFamily()
	{
		return json_decode(file_get_contents(public_path('/data/volumelanguagefamily.json')));
		if (!$this->api) {
			return view('languages.volumes');
		}

		// $full_word =  checkParam('full_word', null, 'optional');
		// $status =  checkParam('status', null, 'optional');
		// $resolution =  checkParam('resolution', null, 'optional');
		$iso             = checkParam('language_code', null, 'optional');
		$root            = checkParam('root', null, 'optional');
		$media           = checkParam('media', null, 'optional');
		$delivery        = checkParam('delivery', null, 'optional');
		$organization_id = checkParam('organization_id', null, 'optional');

		$languages = \Cache::remember('volumeLanguageFamily' . $root . $iso . $media . $delivery . $organization_id,
			2400, function () use ($root, $iso, $media, $delivery, $organization_id) {
				$languages = Language::with('bibles')->with('dialects')
				                     ->with(['dialects.childLanguage' => function ($query) {
					                     $query->select(['id', 'iso']);
				                     }])
				                     ->when($iso, function ($query) use ($iso) {
					                     return $query->where('iso', $iso);
				                     })->when($root, function ($query) use ($root) {
						return $query->where('name', 'LIKE', '%' . $root . '%');
					})->when($root, function ($query) use ($root) {
						return $query->where('name', 'LIKE', '%' . $root . '%');
					})
				                     ->get();
				return fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer());
			});
		return $this->reply($languages);
	}

	/**
	 * Handle the Country Lang route for V2
	 *
	 * // TODO: backwards compatibility - low priority
	 * // TODO: Generation code for img_type & img_size
	 *
	 * @OAS\Get(
	 *     path="/country/countrylang/",
	 *     tags={"Country Language"},
	 *     summary="Returns Languages and the countries associated with them",
	 *     description="Filter languages by a specified country code or filter countries by specified language code. Country flags can also be retrieved by requesting one of the permitted image sizes. Languages can be sorted by the country code (default) and the language code.",
	 *     operationId="v2_country_lang",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Parameter(name="lang_code",in="query",description="Get records by ISO language code", @OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OAS\Parameter(name="country_code",in="query",description="Get records by ISO country code", @OAS\Schema(ref="#/components/schemas/Country/properties/id")),
	 *     @OAS\Parameter(name="additional",in="query",description="Get colon separated list of optional countries", @OAS\Schema(type="integer",enum={0,1},default=0)),
	 *     @OAS\Parameter(name="sort_by",in="query",description="Sort by lang_code or country_code", @OAS\Schema(type="string",enum={"country_code","lang_code"},default="country_code")),
	 *     @OAS\Parameter(name="img_type",in="query",description="Includes a country flag image of the specified file type", @OAS\Schema(type="string",enum={"png","svg"},default="png")),
	 *     @OAS\Parameter(name="img_size",in="query",description="Include country flag in entries in requested size. Note: This parameter accepts any resolution in the format (width)x(height), however, selecting a resolution with an aspect ratio other than 1:1 or 4:3 will likely result in distortion. We encourage you to use a standard size (40x30, 80x60, 160X120, 320X240, 640X480, or 1280X960) because they can be generated much more quickly than other sizes. If this parameter is provided and img_type is omitted, img_type is assumed to be png. This parameter is ignored when img_type is svg.",@OAS\Schema(type="string",example="160X120")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_country_lang"))
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

		if (env('APP_ENV') == 'local') {
			ini_set('memory_limit', '864M');
		}

		// Get and set variables from Params. Both are optional.
		$sort_by            = checkParam('sort_by', null, 'optional');
		$lang_code          = checkParam('lang_code', null, 'optional');
		$country_code       = checkParam('country_code', null, 'optional');
		$country_additional = checkParam('additional', null, 'optional');
		$cache_string       = "v2_country_lang_" . $sort_by . $lang_code . $country_code . $country_additional;

		$countryLang = \Cache::remember($cache_string, 1600,
			function () use ($sort_by, $lang_code, $country_code, $country_additional) {

				// Fetch Languages and add conditional sorting / loading depending on params
				$languages = Language::has('primaryCountry')->has('bibles.filesets')->with('primaryCountry',
					'countries')
				                     ->when($sort_by, function ($q) use ($sort_by) {
					                     return $q->orderBy($sort_by, 'desc');
				                     })->when($lang_code, function ($q) use ($lang_code) {
						return $q->where('iso', $lang_code);
					})->when($country_code, function ($q) use ($country_code) {
						return $q->where('country_id', $country_code);
					})->get();
				if ($country_additional) {
					$languages->load('countries');
				}

				return fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer());
			});
		// Transform and return JSON
		return $this->reply($countryLang);
	}

	/**
	 * WEB:
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create()
	{
		$user = \Auth::user();
		if (!$user->archivist) {
			return $this->setStatusCode(401)->replyWithError("Sorry you must have Archivist Level Permissions");
		}
		$swagger = fetchSwaggerSchema('Language', 'V4');
		return view('languages.create', compact('swagger'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @OAS\Post(
	 *     path="/languages/",
	 *     tags={"Languages"},
	 *     summary="Create a new Language",
	 *     description="Create a new Language",
	 *     operationId="v4_languages.store",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\RequestBody(required=true, description="Fields for User Highlight Creation", @OAS\MediaType(mediaType="application/json",
	 *          @OAS\Schema(ref="#/components/schemas/Language")
	 *     )),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/Language")
	 *         )
	 *     )
	 * )
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if (!Auth::user()->archivist) {
			return $this->setStatusCode(401)->replyWithError("You are not an Archivist");
		}
		$this->validateLanguage($request);
		Language::create($request->all());
		redirect()->route('languages_show', ['id' => $request->id]);
	}

	/**
	 * @param $id
	 *
	 * @OAS\Get(
	 *     path="/languages/{id}",
	 *     tags={"Languages"},
	 *     summary="Return a single Languages",
	 *     description="Returns a single Language",
	 *     operationId="v4_languages.one",
	 *     @OAS\Parameter(name="id", in="path", description="The languages ID", required=true, @OAS\Schema(ref="#/components/schemas/Language/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/Language")
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
	{
		$language = fetchLanguage($id);
		$language->load("translations", "codes", "dialects", "classifications", "countries", "bibles.translations",
			"bibles.filesets", "resources.translations", "resources.links");
		if (!$language) {
			return $this->setStatusCode(404)->replyWithError("Language not found for ID: $id");
		}
		if ($this->api) {
			return $this->reply(fractal()->item($language)->transformWith(new LanguageTransformer()));
		}

		return view('wiki.languages.show', compact('language'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$language = Language::find($id);
		return view('languages.edit', compact('language'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @OAS\Put(
	 *     path="/languages/{id}",
	 *     tags={"Languages"},
	 *     summary="Return a single Languages",
	 *     description="Returns a single Language",
	 *     operationId="v4_languages.update",
	 *     @OAS\Parameter( name="id", in="path", description="The languages ID", required=true, @OAS\Schema(ref="#/components/schemas/Language/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/Language")
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if (!Auth::user()->archivist) {
			return $this->setStatusCode(401)->replyWithError("You are not an Archivist");
		}
		$language = Language::find($id);
		$this->validateLanguage($request);
		$language->fill($request->all())->save();

		return redirect()->route('view_languages.show', ['id' => $request->id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id, Request $request)
	{
		if (!Auth::user()->archivist) {
			return $this->setStatusCode(401)->replyWithError("You are not an Archivist");
		}
		Language::find($id)->delete();
		return redirect()->route('view_languages.index');
	}

	public function validateLanguage(Request $request)
	{
		$latLongRegex = '^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$';
		$validator    = Validator::make($request->all(), [
			'glotto_id'  => ($request->method() == "POST") ? 'alpha_num|unique:languages,glotto_id|max:8|required_if:iso,null|nullable' : 'alpha_num|exists:languages,glotto_id|max:8|required_if:iso,null|nullable',
			'iso'        => ($request->method() == "POST") ? 'alpha|unique:languages,iso|max:3|required_if:glotto_code,null|nullable' : 'alpha|exists:languages,iso|max:3|required_if:glotto_code,null|nullable',
			'iso2B'      => ($request->method() == "POST") ? 'alpha|max:3|unique:languages,iso2B' : 'alpha|max:3',
			'iso2T'      => ($request->method() == "POST") ? 'alpha|max:3|unique:languages,iso2T' : 'alpha|max:3',
			'iso1'       => ($request->method() == "POST") ? 'alpha|max:2|unique:languages,iso1' : 'alpha|max:2',
			'name'       => 'required|string|max:191',
			'autonym'    => 'required|string|max:191',
			'level'      => 'string|max:191|nullable',
			'maps'       => 'string|max:191|nullable',
			'population' => 'integer',
			'latitude'   => 'regex:' . $latLongRegex,
			'longitude'  => 'regex:' . $latLongRegex,
			'country_id' => 'alpha|max:2|exists:countries,id',
		]);

		if ($validator->fails()) {
			if ($this->api) {
				return $this->setStatusCode(422)->replyWithError($validator->errors());
			}
			if (!$this->api) {
				return redirect('dashboard/alphabets/create')->withErrors($validator)->withInput();
			}
		}

	}

}
