<?php

namespace App\Http\Controllers\Bible;

use App\Helpers\AWS\Bucket;
use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleLink;
use App\Models\Bible\Book;
use App\Models\Bible\BookTranslation;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Models\User\AccessGroup;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use App\Traits\AccessControlAPI;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Serializers\DataArraySerializer;
use App\Http\Controllers\APIController;

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
	 * @OA\Get(
	 *     path="/bibles",
	 *     tags={"Bibles"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_bible.all",
	 *     @OA\Parameter(name="bible_id",             in="query", description="The Bible Id", ref="#/components/schemas/Bible/properties/id"),
	 *     @OA\Parameter(name="fcbh_id",              in="query", description="An alternative query name for the bible id", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="media",                in="query", description="If set, will filter results by the type of media for which filesets are available. For a complete list of available media types please see the `/bibles/filesets/media/types` route",
	 *         @OA\Schema(type="string",
	 *              @OA\ExternalDocumentation(
	 *                  description="For a complete list of available media types please see the v4_bible_filesets.types route",
	 *                  url="/docs/swagger/v4#/Bibles/v4_bible_filesets_types"
	 *              )
	 *         )
	 *     ),
	 *     @OA\Parameter(name="language",             in="query", description="The language to filter results by", @OA\Schema(ref="#/components/schemas/Language/properties/name")),
	 *     @OA\Parameter(name="language_name",        in="query", description="The language name to filter results by. For a complete list see the `/languages` route", @OA\Schema(ref="#/components/schemas/Language/properties/name")),
	 *     @OA\Parameter(name="language_code",        in="query", description="The iso code to filter results by. This will return results only in the language specified. For a complete list see the `iso` field in the `/languages` route", @OA\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *     @OA\Parameter(name="language_family_code", in="query", description="The iso code of the trade language to filter results by. This will also return all dialects of a language. For a complete list see the `iso` field in the `/languages` route", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="updated",              in="query", description="The last time updated", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="organization_id",      in="query", description="The owning organization to return bibles for. For a complete list of ids see the `/organizations` route", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="sort_by",              in="query", description="The any field to within the bible model may be selected as the value for this `sort_by` param.", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="sort_dir",             in="query", description="The direction to sort by the field specified in `sort_by`. Either `asc` or `desc`", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="bucket_id",            in="query", description="The bucket_id to filter results by. At the moment there are two buckets provided `dbp-dev` & `dbs-web`", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="filter_by_fileset",    in="query", description="This field defaults to true but when set to false will return all Bible entries regardless of whether or not the API has content for that biblical text.", @OA\Schema(type="string")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible.one"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
	{
		if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');
		// Return the documentation if it's not an API request
		if (!$this->api) {
			$ids = checkParam('ids', null, 'optional');
			$ids = explode(',',$ids);
			$bibles = Bible::with('translations')->whereIn('id',$ids)->get();
			return view('bibles.index', compact('bibles'));
		}

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
		$paginate           = checkParam('paginate', null, 'optional') ?? false;
		$filter             = checkParam('filter', null, 'optional') ?? false;

		$access_control = $this->accessControl($this->key, "api");

		$cache_string = 'bibles' . $dam_id . '_' . $media . '_' . $language . '_' . $include_regionInfo . $full_word . '_' . $iso . '_' . $updated . '_' . $organization . '_' . $sort_by . '_' . $sort_dir . '_' . $fileset_filter . '_' . $country . '_' . $bucket . $access_control->string . $paginate. $filter;
		\Cache::forget($cache_string);
		$bibles = \Cache::remember($cache_string, 1600, function () use ($dam_id, $hide_restricted, $media, $filter, $language, $full_word, $iso, $updated, $organization, $sort_by, $sort_dir, $fileset_filter, $country, $bucket, $include_alt_names, $include_regionInfo, $access_control, $paginate) {
			$bibles = Bible::with(['translatedTitles', 'language', 'filesets' => function ($query) use ($bucket, $access_control, $hide_restricted) {
				if($bucket) $query->where('bucket_id', $bucket);
				if($hide_restricted) $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
			}])
			->has('translations')->has('language')
			->when($filter, function ($q) use($filter) {
				$q->whereHas('translatedTitles', function ($query) use($filter) {
					$query->where('name', 'like', '%'.$filter.'%');
				});
			})
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
					$q->where('id', $dam_id)->orWhere('id',substr($dam_id,0,-4))->orWhere('id',substr($dam_id,0,-2));
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
				})->orderBy('priority', 'desc');

			if($paginate) {
				$queryParams = array_diff_key($_GET, array_flip(['page']));
				$paginator = $bibles->paginate($paginate);
				$paginator->appends($queryParams);
				$bibles = $paginator->getCollection();
			} else {
				$bibles = $bibles->get();
			}

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
			if($paginate) return fractal($bibles, new BibleTransformer())->paginateWith(new IlluminatePaginatorAdapter($paginator))->serializeWith(new DataArraySerializer());
			return fractal($bibles, new BibleTransformer())->serializeWith($this->serializer);
		});
		return $this->reply($bibles);
	}

	public function archival()
    {
        if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');
        $iso                = checkParam('iso', null, 'optional');
        $organization_id    = checkParam('organization_id', null, 'optional');
        $organization = '';
        if($organization_id) {
		    $organization = (!is_numeric($organization_id)) ? Organization::with('relationships')->orWhere('slug',$organization_id)->first() : Organization::with('relationships')->where('id',$organization_id)->first();
	        $organization_id = $organization->relationships->where('type','member')->pluck('organization_child_id');
	        $organization_id->push($organization->id);
        }
        $country              = checkParam('country', null, 'optional');
        $include_regionInfo   = checkParam('include_region_info', null, 'optional');
        $include_linkedBibles = checkParam('include_linked_bibles', null, 'optional');
        $dialects             = checkParam('include_dialects', null, 'optional');
	    $language             = null;
	    $bucket               = checkParam('bucket|bucket_id', null, 'optional');

        if($iso) {
            $language = Language::where('iso',$iso)->with('dialects')->first();
            if(!$language) return $this->setStatusCode(404)->replyWithError("Language not found for provided iso");
        }

        $cache_string = 'bibles_archival'.@$language->id.$organization.$country.$include_regionInfo.$dialects.$include_linkedBibles.$bucket;
		if(env('APP_ENV')) Cache::forget($cache_string);
        $bibles = Cache::remember($cache_string, 1600, function () use ($language,$organization_id,$country,$include_regionInfo,$dialects,$include_linkedBibles,$bucket) {
            $bibles = Bible::with(['translatedTitles', 'language','country','filesets.copyrightOrganization'])->withCount('links')
                ->has('translations')->has('language')
                ->when($country, function ($q) use ($country) {
                    $q->whereHas('language.countries', function ($query) use ($country) {
                        $query->where('country_id', $country);
                    });
                })
                ->when($language, function ($q) use ($language,$dialects) {
                   $q->where('language_id', $language->id);
                   if($dialects) $q->orWhereIn('language_id',$language->dialects->pluck('dialect_id'));
                })
	            ->when($bucket, function ($q) use($bucket) {
		            $q->whereHas('filesets', function ($q) use ($bucket) {
			            $q->where('bucket_id', $bucket);
		            })->get();
	            })
                ->when($organization_id, function ($q) use ($organization_id) {
                    $q->whereHas('organizations', function ($q) use ($organization_id) {
                        $q->whereIn('organization_id', $organization_id);
                    })->orWhereHas('links', function ($q) use ($organization_id) {
	                    $q->whereIn('organization_id', $organization_id);
                    })->get();
                })->orderBy('priority', 'desc')
                ->get();

	        $language = Language::where('iso','eng')->first();
            foreach ($bibles as $bible) {
            	$bible->english_language_id = $language->id;
            }

	        if ($include_regionInfo) $bibles->load('country');

            return fractal($bibles, new BibleTransformer())->serializeWith($this->serializer);
        });
        return $this->reply($bibles);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{

		request()->validate([
			'id'                  => 'required|unique:dbp.bibles,id|max:24',
			'iso'                 => 'required|exists:dbp.languages,iso',
			'translations.*.name' => 'required',
			'translations.*.iso'  => 'required|exists:dbp.languages,iso',
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
	 * @OA\Get(
	 *     path="/bibles/{id}",
	 *     tags={"Bibles"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_bible.one",
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Bible id", @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible.one")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible.one"))
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
        $bible = Bible::with('filesets.organization', 'translations', 'books.book', 'links', 'organizations.logo','organizations.logoIcon','organizations.translations', 'alphabet.primaryFont','equivalents')->find($id);
		if (!$bible) return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $id]));
		if (!$this->api) return view('bibles.show', compact('bible'));

		return $this->reply(fractal($bible,new BibleTransformer())->serializeWith($this->serializer));
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
	 * @OA\Get(
	 *     path="/bibles/{id}/book/",
	 *     tags={"Bibles"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_bible.books",
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Bible id", @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OA\Parameter(name="book_id", in="query", description="The Books id", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible.books")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible.books")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible.books"))
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
		$testament = checkParam('testament',null,'optional');

		$translation_languages = checkParam('language_codes', null, 'optional');
		if ($translation_languages) {
			$translation_languages = explode('|', $translation_languages);
		}
		$bible = Bible::find($bible_id);
		//BookTranslation::all()
		$bible_books = BibleBook::where('bible_id', $bible_id)->select('book_id')->distinct()->get()->pluck('book_id');
		$books = BookTranslation::with('book')->where('language_id',$bible->language_id)
					->when($testament, function ($q) use ($testament) {
					    $q->where('book_testament',$testament);
					})
					->when($book_id, function ($q) use ($book_id) {
						$q->where('id', $book_id);
					})->whereIn('book_id',$bible_books)->get();
		$books = $books->sortBy('book.'.$bible->versification.'_order');
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
			'iso'                 => 'required|exists:dbp.languages,iso',
			'translations.*.name' => 'required',
			'translations.*.iso'  => 'required|exists:dbp.languages,iso',
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
