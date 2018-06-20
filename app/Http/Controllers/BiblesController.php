<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\OrganizationTranslation;
use App\Models\User\Access;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
use Illuminate\Support\Facades\Cache;
use App\Traits\AccessControlAPI;

class BiblesController extends APIController
{

	use AccessControlAPI;


	/**
	 * Display a listing of the bibles.
	 *
	 * @deprecated status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @deprecated dbp_agreement (optional): [true|false] Whether or not a DBP Agreement has been executed between FCBH and the organization to whom the volume belongs.
	 * @deprecated expired (optional): [true|false] Whether the volume as passed its expiration or not.
	 * @deprecated resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 * @deprecated delivery (optional): [web|web_streaming|download|download_text|mobile|sign_language|streaming_url|local_bundled|podcast|mp3_cd|digital_download| bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming_url|mobile".  'any' means any of the supported methods (this list may change over time) i.e. approved for something. 'none' means volumes that are not approved for any of the supported methods. All volumes are returned by default.
	 *
	 * @param dam_id (optional): the volume internal DAM ID. Can be used to restrict the response to only DAM IDs that contain with 'N2' for example
	 * @param fcbh_id (optional): the volume FCBH DAM ID. Can be used to restrict the response to only FCBH DAM IDs that contain with 'N2' for example
	 * @param media (optional): [text|audio|video] the format of assets the caller is interested in. This specifies if you only want volumes available in text or volumes available in audio.
	 * @param language (optional): Filter the versions returned to a specified native or English language language name. For example return all the 'English' volumes.
	 * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param language_code (optional): the three letter language code.
	 * @param language_family_code (optional): the three letter language code for the language family.
	 * @param updated (optional): YYYY-MM-DD. This is used to get volumes that were modified since the specified date.
	 * @param organization_id (optional): Organization id of volumes to return.
	 * @param sort_by (optional): [ dam_id | volume_name | language_name | language_english | language_family_code | language_family_name | version_code | version_name | version_english ] Primary criteria by which to sort.  The default is 'dam_id'.
	 *
	 * @OAS\Get(
	 *     path="/bibles",
	 *     tags={"Bibles"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_bible.all",
	 *     @OAS\Parameter(name="bible_id",             in="query", description="The Bible Id", ref="#/components/schemas/Bible/properties/id"),
	 *     @OAS\Parameter(name="fcbh_id",              in="query", description="An alternative query name for the bible id", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="media",                in="query", description="If set, will filter results by the type of media for which filesets are available. For a complete list of available media types please see the `/bibles/filesets/media/types` route",
	 *         @OAS\Schema(type="string",
	 *              @OAS\ExternalDocumentation(
	 *                  description="For a complete list of available media types please see the v4_bible_filesets.types route",
	 *                  url="/docs/swagger/v4#/Bibles/v4_bible_filesets_types"
	 *              )
	 *         )
	 *     ),
	 *     @OAS\Parameter(name="language",             in="query", description="The language to filter results by", @OAS\Schema(ref="#/components/schemas/Language/properties/name")),
	 *     @OAS\Parameter(name="language_name",        in="query", description="The language name to filter results by. For a complete list see the `/languages` route", @OAS\Schema(ref="#/components/schemas/Language/properties/name")),
	 *     @OAS\Parameter(name="language_code",        in="query", description="The iso code to filter results by. This will return results only in the language specified. For a complete list see the `iso` field in the `/languages` route", @OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OAS\Parameter(name="language_family_code", in="query", description="The iso code of the trade language to filter results by. This will also return all dialects of a language. For a complete list see the `iso` field in the `/languages` route", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="updated",              in="query", description="The last time updated", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="organization_id",      in="query", description="The owning organization to return bibles for. For a complete list of ids see the `/organizations` route", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="sort_by",              in="query", description="The any field to within the bible model may be selected as the value for this `sort_by` param.", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="sort_dir",             in="query", description="The direction to sort by the field specified in `sort_by`. Either `asc` or `desc`", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="bucket_id",            in="query", description="The bucket_id to filter results by. At the moment there are two buckets provided `dbp-dev` & `dbs-web`", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="filter_by_fileset",    in="query", description="This field defaults to true but when set to false will return all Bible entries regardless of whether or not the API has content for that biblical text.", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_bible.one"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
	{

		if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');
		// Return the documentation if it's not an API request
		if (!$this->api) return view('bibles.index');

		$dam_id             = checkParam('dam_id|fcbh_id|bible_id', null, 'optional');
		$media              = checkParam('media', null, 'optional');
		$language           = checkParam('language', null, 'optional');
		$full_word          = checkParam('full_word|language_name', null, 'optional');
		$iso                = checkParam('language_family_code|language_code', null, 'optional');
		$updated            = checkParam('updated', null, 'optional');
		$organization       = checkParam('organization_id', null, 'optional');
		$sort_by            = checkParam('sort_by', null, 'optional');
		$sort_dir           = checkParam('sort_dir', null, 'optional') ?? 'asc';
		$fileset_filter     = boolval(checkParam('filter_by_fileset', null, 'optional')) ?? true;
		$include_alt_names  = checkParam('include_alt_names', null, 'optional');
		$include_regionInfo = checkParam('include_region_info', null, 'optional');
		$country            = checkParam('country', null, 'optional');
		$bucket             = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$hide_restricted    = checkParam('hide_restricted', null, 'optional') ?? true;

		$access_control = $this->accessControl($this->key, "api");

		$cache_string = 'bibles' . $dam_id . '_' . $media . '_' . $language . '_' . $include_regionInfo . $full_word . '_' . $iso . '_' . $updated . '_' . $organization . '_' . $sort_by . '_' . $sort_dir . '_' . $fileset_filter . '_' . $country . '_' . $bucket . $access_control->string;
		\Cache::forget($cache_string);
		$bibles = \Cache::remember($cache_string, 1600, function () use ($dam_id, $hide_restricted, $media, $language, $full_word, $iso, $updated, $organization, $sort_by, $sort_dir, $fileset_filter, $country, $bucket, $include_alt_names, $include_regionInfo, $access_control) {
			$bibles = Bible::with(['translatedTitles', 'language', 'filesets' => function ($query) use ($bucket, $access_control, $hide_restricted) {
				if($bucket) $query->where('bucket_id', $bucket);
				if(!$hide_restricted) $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
			}])
			->has('translations')->has('language')
			->when($fileset_filter, function ($q) {
			    $q->has('filesets.files');
			})
			->when($bucket, function ($q) use($bucket) {
				$q->whereHas('filesets', function ($q) use ($bucket) {
					$q->where('bucket_id', $bucket);
				})->get();
			})
			->when($country, function ($q) use ($country) {
			    $q->whereHas('country', function ($query) use ($country) {
			        $query->where('countries.id', $country);
			    });
			})
			->when($iso, function ($q) use ($iso) {
			    $q->where('iso', $iso);
			})
			->when($organization, function ($q) use ($organization) {
			    $q->whereHas('organizations', function ($q) use ($organization) {
			        $q->where('organization_id', $organization);
			    })->get();
			})->when($dam_id, function ($q) use ($dam_id) {
					$q->where('id', $dam_id);
				})->when($media, function ($q) use ($media) {
					switch ($media) {
						case "video": {$q->has('filesetFilm');break;}
						case "audio": {$q->has('filesetAudio');break;}
						case "text": {$q->has('filesetText');break;}
					}
				})->when($updated, function ($q) use ($updated) {
					$q->where('updated_at', '>', $updated);
				})->when($sort_by, function ($q) use ($sort_by, $sort_dir) {
					$q->orderBy($sort_by, $sort_dir);
				})->orderBy('priority', 'desc')->get();

			if ($include_alt_names) $bibles->load('language.translations');
			if ($include_regionInfo) $bibles->load('country');

			if ($language) {
				$bibles = $bibles->filter(function ($bible) use ($language, $full_word) {
					$altNameList = [];
					if (isset($bible->language->translations)) {
						$altNameList = $bible->language->translations->pluck('name')->toArray();
					}
					if (isset($full_word)) {
						return ($bible->language->name == $language) || in_array($language, $altNameList);
					}
					return (stripos($bible->language->name,
							$language) || ($bible->language->name == $language) || stripos(implode($altNameList),
							$language));
				});
			}

			if ($this->v == 2) $bibles->load('language.parent.parentLanguage', 'alphabet', 'organizations');
			return fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer);
		});
		return $this->reply($bibles);
	}

	public function archival()
    {
        if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');
        $iso               = checkParam('iso', null, 'optional');
        $organization      = checkParam('organization_id', null, 'optional');
        $country           = checkParam('country', null, 'optional');
        $include_regionInfo = checkParam('include_region_info', null, 'optional');

        $cache_string = 'bibles_archival'.$iso.$organization.$country.$include_regionInfo;
		Cache::forget($cache_string);
        $bibles = Cache::remember($cache_string, 1600, function () use ($iso,$organization,$country,$include_regionInfo) {
            $bibles = Bible::with(['translatedTitles', 'language','filesets.copyrightOrganization'])->withCount('links')
                ->has('translations')->has('language')
                ->when($country, function ($q) use ($country) {
                    $q->whereHas('language.primaryCountry', function ($query) use ($country) {
                        $query->where('country_id', $country);
                    });
                })
                ->when($iso, function ($q) use ($iso) {
                    $q->where('iso', $iso);
                })
                ->when($organization, function ($q) use ($organization) {
                    $q->whereHas('organizations', function ($q) use ($organization) {
                        $q->where('organization_id', $organization)->orWhere('slug',$organization);
                    })->orWhereHas('links', function ($q) use ($organization) {
	                    $q->where('provider', $organization);
                    })->get();
                })->orderBy('priority', 'desc')
                ->get();

            if ($include_regionInfo) $bibles->load('country');

            return fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer);
        });
        return $this->reply($bibles);
    }


	/**
	 * v2_volume_history
	 *
	 * @link https://api.dbp.dev/library/volumehistory?key=1234&v=2
	 *
	 * @OAS\Get(
	 *     path="/library/volumehistory",
	 *     tags={"Library Catalog"},
	 *     summary="",
	 *     description="",
	 *     operationId="v2_volume_history",
	 *     @OAS\Parameter(name="limit",  in="query", description="The Number of records to return"),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_bible.one"))
	 *     )
	 * )
	 *
	 * A Route to Review The Last 500 Recent Changes to The Bible Resources
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function history()
	{
		if (!$this->api) return view('bibles.history');

		$limit  = checkParam('limit', null, 'optional') ?? 500;
		$bibles = Bible::select(['id', 'updated_at'])->take($limit)->get();
		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
	}

	/**
	 *
	 * Get the list of versions defined in the system
	 *
	 * @link https://api.dbp.dev/library/version?key=1234&v=2
	 *
	 * @param code (optional): Get the entry for a three letter version code.
	 * @param name (optional): Get the entry for a part of a version name in either native language or English.
	 * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
	 *
	 * @OAS\Get(
	 *     path="/library/version",
	 *     tags={"Library Catalog"},
	 *     summary="Returns Audio File path information",
	 *     description="This call returns the file path information for audio files for a volume. This information can be used with the response of the /audio/location call to create a URI to retrieve the audio files.",
	 *     operationId="v2_library_version",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="code", in="query", description="The abbreviated `BibleFileset` id created from the three letters identifier after the iso code", required=true, @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="name", in="query", description="The name of the version in the language that it's written in", @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(name="sort_by", in="query", description="The name of the version in english", @OAS\Schema(type="string",title="encoding")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_library_version"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v2_library_version",
	 *     description="The various version ids in the old version 2 style",
	 *     title="v2_library_version",
	 *     @OAS\Xml(name="v2_library_version"),
	 *     @OAS\Property(property="version_code",type="string",description="The abbreviated `BibleFileset` id created from the three letters identifier after the iso code"),
	 *     @OAS\Property(property="version_name",type="string",description="The name of the version in the language that it's written in"),
	 *     @OAS\Property(property="english_name",type="string",description="The name of the version in english")
	 * )
	 *
	 * @return json
	 */
	public function libraryVersion()
	{
		$code = checkParam('code', null, 'optional');
		$name = checkParam('name', null, 'optional');
		$sort = checkParam('sort_by', null, 'optional');

		$versions = Cache::remember('v2_library_version_' . $code . $name . $sort, 1600,
			function () use ($code, $name, $sort) {
				$versions = BibleFileset::with('bible.translations')->has('bible.translations')->where('bucket_id',
					env('FCBH_AWS_BUCKET'))
				                        ->when($code, function ($q) use ($code) {
					                        $q->where('id', $code);
				                        })->when($sort, function ($q) use ($sort) {
						$q->orderBy($sort);
					})->get();
				foreach ($versions as $version) {
					$currentTranslations = $version->bible->first();
					$version_name        = $currentTranslations->translations->where('iso', '!=', 'eng')->first();
					$english_name        = $currentTranslations->translations->where('iso', '=', 'eng')->first();
					$output[]            = [
						'version_code' => substr($version->id, 3, 3),
						'version_name' => isset($version_name) ? ($version_name->name != $english_name->name) ? $version_name->name : "" : "",
						'english_name' => $english_name ? $english_name->name : "",
					];
				}
				return $output;
			});
		return $this->reply($versions);
	}

	/**
	 *
	 * @link https://api.dbp.dev/library/metadata?key=1234&pretty&v=2
	 *
	 * @OAS\Get(
	 *     path="/library/metadata",
	 *     tags={"Library Catalog"},
	 *     summary="This returns copyright and associated organizations info.",
	 *     description="",
	 *     operationId="v2_library_metadata",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OAS\MediaType(mediaType="text/yaml",        @OAS\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OAS\MediaType(mediaType="text/csv",         @OAS\Schema(ref="#/components/schemas/v2_library_metadata"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v2_library_metadata",
	 *     description="The various version ids in the old version 2 style",
	 *     title="v2_library_version",
	 *     @OAS\Xml(name="v2_library_version"),
	 *     @OAS\Property(property="dam_id",         ref="#/components/schemas/BibleFileset/id"),
	 *     @OAS\Property(property="mark",           ref="#/components/schemas/BibleFilesetCopyright/copyright"),
	 *     @OAS\Property(property="volume_summary", ref="#/components/schemas/BibleFilesetCopyright/copyright_description"),
	 *     @OAS\Property(property="organization", type="object",
	 *     @OAS\AdditionalProperties(
	 *         type="object",
	 *         @OAS\Property(property="organization_id",       ref="#/components/schemas/Organization/id"),
	 *         @OAS\Property(property="organization",          ref="#/components/schemas/Organization/name"),
	 *         @OAS\Property(property="organization_english",  ref="#/components/schemas/Organization/name"),
	 *         @OAS\Property(property="organization_role",     ref="#/components/schemas/Organization/role"),
	 *         @OAS\Property(property="organization_url",      ref="#/components/schemas/Organization/url"),
	 *         @OAS\Property(property="organization_donation", ref="#/components/schemas/Organization/donation"),
	 *         @OAS\Property(property="organization_address",  ref="#/components/schemas/Organization/address"),
	 *         @OAS\Property(property="organization_address2", ref="#/components/schemas/Organization/address2"),
	 *         @OAS\Property(property="organization_city",     ref="#/components/schemas/Organization/city"),
	 *         @OAS\Property(property="organization_state",    ref="#/components/schemas/Organization/state"),
	 *         @OAS\Property(property="organization_country",  ref="#/components/schemas/Organization/country"),
	 *         @OAS\Property(property="organization_zip",      ref="#/components/schemas/Organization/zip"),
	 *         @OAS\Property(property="organization_phone",    ref="#/components/schemas/Organization/phone")
	 *     )),
	 * )
	 *
	 * @return mixed
	 */
	public function libraryMetadata()
	{
		if (env('APP_ENV') == 'local') {
			ini_set('memory_limit', '864M');
		}
		$fileset_id = checkParam('dam_id', null, 'optional');
		$bucket_id  = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');

		\Cache::forget('v2_library_metadata' . $fileset_id);
		$metadata = Cache::remember('v2_library_metadata' . $fileset_id, 1600,
			function () use ($fileset_id, $bucket_id) {
				$metadata = BibleFileset::has('copyright')->with('copyright.organizations', 'copyright.role.roleTitle',
					'bible')->when($fileset_id, function ($q) use ($fileset_id) {
					$q->where('id', $fileset_id)->first();
				})->where('bucket_id', $bucket_id)->where('set_type_code', '!=', 'text_format');

				if ($fileset_id) {
					return fractal()->item($metadata->first())->serializeWith($this->serializer)->transformWith(new BibleTransformer());
				}
				return fractal()->collection($metadata->get())->serializeWith($this->serializer)->transformWith(new BibleTransformer());
			});

		return $this->reply($metadata);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{

		request()->validate([
			'id'                  => 'required|unique:bibles,id|max:24',
			'iso'                 => 'required|exists:languages,iso',
			'translations.*.name' => 'required',
			'translations.*.iso'  => 'required|exists:languages,iso',
			'date'                => 'integer',
		]);

		$bible = \DB::transaction(function () {
			$bible = new Bible();
			$bible = $bible->create(request()->only(['id', 'date', 'script', 'portions', 'copyright', 'derived', 'in_progress', 'notes', 'iso']));
			$bible->translations()->createMany(request()->translations);
			$bible->organizations()->attach(request()->organizations);
			$bible->equivalents()->createMany(request()->equivalents);
			$bible->links()->createMany(request()->links);
			return $bible;
		});

		return redirect()->route('view_bibles.show', ['id' => $bible->id]);
	}

	/**
	 * Description:
	 * Display the bible meta data for the specified ID.
	 *
	 * @OAS\Get(
	 *     path="/bibles/{id}",
	 *     tags={"Bibles"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_bible.one",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Bible id", @OAS\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_bible.one"))
	 *     )
	 * )
	 *
	 * \\TODO: Move Links
	 *
	 * @param  string $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$bible = Bible::with('filesets.organization', 'translations', 'books.book', 'links', 'organizations.logo','organizations.logoIcon', 'alphabet.primaryFont','equivalents')->find($id);
		if (!$bible) return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $id]));
		if (!$this->api) return view('bibles.show', compact('bible'));

		return $this->reply(fractal()->item($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
	}

	public function manage($id)
	{
		$bible = Bible::with('filesets')->find($id);
		if (!$bible) {
			return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $id]));
		}

		return view('bibles.manage', compact('bible'));
	}

	/**
	 *  Query books with the optional constraints of bible_id, book_id and language translations
	 *
	 * @OAS\Get(
	 *     path="/bibles/{id}/book/",
	 *     tags={"Bibles"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_bible.books",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Bible id", @OAS\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OAS\Parameter(name="book_id", in="query", description="The Books id", @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_bible.books")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_bible.books")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_bible.books"))
	 *     )
	 * )
	 *
	 * @param string $bible_id
	 * @param string|null $book_id
	 *
	 * @return APIController::reply()
	 */
	public function books($bible_id, $book_id = null)
	{
		if (!$this->api) {
			return view('bibles.books.index');
		}

		$book_id = checkParam('book_id', $book_id, 'optional');

		$translation_languages = checkParam('language_codes', null, 'optional');
		if ($translation_languages) {
			$translation_languages = explode('|', $translation_languages);
		}

		$bible_books = BibleBook::where('bible_id', $bible_id)->first();
		$books       = Book::when($translation_languages, function ($q) use ($translation_languages) {
			$q->with(['translations' => function ($query) use ($translation_languages) {
				$query->whereIn('iso', $translation_languages);
			}]);
		})->when($bible_id, function ($q) use ($bible_id, $bible_books) {
			if (isset($bible_books)) {
				$q->whereHas('bible', function ($query) use ($bible_id) {
					$query->where('bible_id', $bible_id);
				});
			}
			$q->with(['bible' => function ($query) use ($bible_id) {
				$query->where('bible_id', $bible_id)->select('id');
			}]);
		})->when($book_id, function ($q) use ($book_id) {
			$q->where('id', $book_id);
		})->orderBy('protestant_order')->get();

		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer)->toArray());
	}

	public function edit($id)
	{
		$bible = Bible::with('translations.language')->find($id);
		if (!$this->api) {
			$languages     = Language::select(['iso', 'name'])->orderBy('iso')->get();
			$organizations = OrganizationTranslation::select(['name', 'organization_id'])->where('language_iso',
				'eng')->get();
			$alphabets     = Alphabet::select('script')->get();
			return view('bibles.edit', compact('languages', 'organizations', 'alphabets', 'bible'));
		}

		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$languages     = Language::select(['iso', 'name'])->get();
		$organizations = OrganizationTranslation::select(['name', 'organization_id'])->where('language_iso',
			'eng')->get();
		$alphabets     = Alphabet::select('script')->get();
		return view('bibles.create', compact('languages', 'organizations', 'alphabets'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update($id)
	{

		request()->validate([
			'id'                  => 'required|max:24',
			'iso'                 => 'required|exists:languages,iso',
			'translations.*.name' => 'required',
			'translations.*.iso'  => 'required|exists:languages,iso',
			'date'                => 'integer',
		]);

		$bible = \DB::transaction(function () use ($id) {
			$bible = Bible::with('translations', 'organizations', 'equivalents', 'links')->find($id);
			$bible->update(request()->only(['id', 'date', 'script', 'portions', 'copyright', 'derived', 'in_progress', 'notes', 'iso']));

			if (request()->translations) {
				foreach ($bible->translations as $translation) {
					$translation->delete();
				}
				foreach (request()->translations as $translation) {
					if ($translation['name']) {
						$bible->translations()->create($translation);
					}
				}
			}

			if (request()->organizations) {
				$bible->organizations()->sync(request()->organizations);
			}

			if (request()->equivalents) {
				foreach ($bible->equivalents as $equivalent) {
					$equivalent->delete();
				}
				foreach (request()->equivalents as $equivalent) {
					if ($equivalent['equivalent_id']) {
						$bible->equivalents()->create($equivalent);
					}
				}
			}

			if (request()->links) {
				foreach ($bible->links as $link) {
					$link->delete();
				}
				foreach (request()->links as $link) {
					if ($link['url']) {
						$bible->links()->create($link);
					}
				}
			}

			return $bible;
		});

		return redirect()->route('view_bibles.show', ['id' => $bible->id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		// TODO: Generate Delete Model for Bible
	}
}
