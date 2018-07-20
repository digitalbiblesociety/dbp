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
			$languages = Language::select(['id', 'iso2B', 'iso', 'name'])->with('bibles.filesets')
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
		$language->load("translations", "codes", "dialects", "classifications", "countries", "primaryCountry", "bibles.translations.language", "bibles.filesets", "resources.translations", "resources.links");
		if(!$language) return $this->setStatusCode(404)->replyWithError("Language not found for ID: $id");
		if($this->api) return $this->reply(fractal($language, new LanguageTransformer()));

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
