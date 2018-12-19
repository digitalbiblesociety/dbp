<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BookTranslation;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
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
     * @OA\Get(
     *     path="/bibles",
     *     tags={"Bibles"},
     *     summary="Returns Bibles",
     *     description="The base bible route returning by default bibles and filesets that your key has access to",
     *     operationId="v4_bible.all",
     *     @OA\Parameter(
     *          name="language_code",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Language/properties/iso"),
     *          description="The iso code to filter results by. This will return results only in the language specified.
                    For a complete list see the `iso` field in the `/languages` route",
     *     ),
     *     @OA\Parameter(
     *          name="organization_id",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The owning organization to return bibles for. For a complete list of ids see the route
                    `/organizations`."
     *     ),
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The asset_id to filter results by. There are two buckets provided `dbp.test` & `dbs-web`"
     *     ),
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
        $language_code      = checkParam('language_id|language_code');
        $organization       = checkParam('organization_id');
        $country            = checkParam('country');
        $asset_id           = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $show_restricted    = checkParam('show_restricted') ?? false;
        $media              = checkParam('media');

        $access_control = $this->accessControl($this->key);

        $cache_string = strtolower('bibles'.$language_code.$organization.$country.$asset_id.$access_control->string.$media);
        $bibles = \Cache::remember($cache_string, 1600, function () use ($show_restricted, $language_code, $organization, $country, $asset_id, $access_control, $media) {
            $bibles = Bible::withRequiredFilesets($asset_id, $access_control, $show_restricted, $media)
                ->leftJoin('bible_translations as ver_title', function ($join) {
                    $join->on('ver_title.bible_id', '=', 'bibles.id')->where('ver_title.vernacular', 1);
                })
                ->leftJoin('bible_translations as current_title', function ($join) {
                    $join->on('current_title.bible_id', '=', 'bibles.id')
                         ->where('current_title.language_id', '=', $GLOBALS['i18n_id']);
                })
                ->leftJoin('languages as languages', function ($join) {
                    $join->on('languages.id', '=', 'bibles.language_id');
                })
                ->leftJoin('language_translations as language_autonym', function ($join) {
                    $join->on('language_autonym.language_source_id', '=', 'bibles.language_id')
                         ->on('language_autonym.language_translation_id', '=', 'bibles.language_id')
                         ->orderBy('priority', 'desc');
                })
                ->leftJoin('language_translations as language_current', function ($join) {
                    $join->on('language_current.language_source_id', '=', 'bibles.language_id')
                         ->where('language_current.language_translation_id', '=', $GLOBALS['i18n_id'])
                         ->orderBy('priority', 'desc');
                })
                ->when($language_code, function ($q) use ($language_code) {
                    $language = Language::where('iso', $language_code)->orWhere('id', $language_code)->first();
                    $q->where('bibles.language_id', $language->id);
                })
                ->when($country, function ($q) use ($country) {
                    $q->whereHas('country', function ($query) use ($country) {
                        $query->where('countries.id', $country);
                    });
                })
                ->when($organization, function ($q) use ($organization) {
                    $q->whereHas('organizations', function ($q) use ($organization) {
                        $q->where('organization_id', $organization);
                    })->get();
                })
                ->select(
                    \DB::raw(
                        'MIN(current_title.name) as ctitle,
                        MIN(ver_title.name) as vtitle,
                        MIN(bibles.language_id) as language_id,
                        MIN(languages.iso) as iso,
                        MIN(bibles.date) as date,
                        MIN(language_autonym.name) as language_autonym,
                        MIN(language_current.name) as language_current,
                        MIN(bibles.priority) as priority,
                        MIN(bibles.id) as id'
                    )
                )
                ->orderBy('bibles.priority', 'desc')->groupBy('bibles.id')->get();
            return fractal($bibles, new BibleTransformer(), new DataArraySerializer());
        });

        return $this->reply($bibles);
    }

    public function archival()
    {
        $iso                = checkParam('iso');
        $organization_id    = checkParam('organization_id');
        $organization = '';
        if ($organization_id) {
            $organization = (!is_numeric($organization_id)) ? Organization::with('relationships')->orWhere('slug', $organization_id)->first() : Organization::with('relationships')->where('id', $organization_id)->first();
            $organization_id = $organization->relationships->where('type', 'member')->pluck('organization_child_id');
            $organization_id->push($organization->id);
        }
        $country              = checkParam('country');
        $include_regionInfo   = checkParam('include_region_info');
        $include_linkedBibles = checkParam('include_linked_bibles');
        $dialects             = checkParam('include_dialects');
        $language             = null;
        $asset_id             = checkParam('bucket|bucket_id|asset_id');

        $language = $iso ? Language::where('iso', $iso)->with('dialects')->first() : null;
        $cache_string = strtolower('bibles_archival'.$iso.$organization.$country.$include_regionInfo.$dialects.$include_linkedBibles.$asset_id);
        $bibles = \Cache::remember($cache_string, 1600, function () use ($language, $organization_id, $country, $include_regionInfo, $dialects, $asset_id) {
            $bibles = Bible::with(['translatedTitles', 'language','country','filesets.copyrightOrganization'])->withCount('links')
                ->has('translations')->has('language')
                ->when($country, function ($q) use ($country) {
                    $q->whereHas('language.countries', function ($query) use ($country) {
                        $query->where('country_id', $country);
                    });
                })
                ->when($language, function ($q) use ($language, $dialects) {
                    $q->where('language_id', $language->id);
                    if ($dialects) {
                        $q->orWhereIn('language_id', $language->dialects->pluck('dialect_id'));
                    }
                })
                ->when($asset_id, function ($q) use ($asset_id) {
                    $q->whereHas('filesets', function ($q) use ($asset_id) {
                        $q->where('asset_id', $asset_id);
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

            $language = Language::where('iso', 'eng')->first();
            foreach ($bibles as $bible) {
                $bible->english_language_id = $language->id;
            }

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
     *     @OA\Parameter(name="id",in="path",required=true,@OA\Schema(ref="#/components/schemas/Bible/properties/id")),
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
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $access_control = $this->accessControl($this->key);
        $cache_string = strtolower('bible_show_response'.$id.$access_control->string);
        $bible = \Cache::remember($cache_string, 2400, function() use($access_control,$id) {
            return Bible::with(['translations', 'books.book', 'links', 'organizations.logo','organizations.logoIcon','organizations.translations', 'alphabet.primaryFont','equivalents',
                'filesets' => function ($query) use ($access_control) {
                    $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
                }])->find($id);
        });
        if (!$bible) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $id]));
        }
        if (!$this->api) {
            return view('bibles.show', compact('bible'));
        }

        return $this->reply(fractal($bible, new BibleTransformer(), $this->serializer));
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
     *
     * @OA\Get(
     *     path="/bibles/{id}/book/",
     *     tags={"Bibles"},
     *     summary="Returns a list of translated book names and general information for the given Bible",
     *     description="The actual list of books may vary from fileset to fileset. For example, a King James Fileset may
               contain deuterocanonical books that are missing from one of it's sibling filesets nested within the bible
               parent.",
     *     operationId="v4_bible.books",
     *     @OA\Parameter(name="id",in="path",required=true,@OA\Schema(ref="#/components/schemas/Bible/properties/id")),
     *     @OA\Parameter(name="book_id",in="query",@OA\Schema(ref="#/components/schemas/Book/properties/id")),
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
        $book_id   = checkParam('book_id', false, $book_id);
        $testament = checkParam('testament');

        $bible = Bible::find($bible_id);

        $books = BibleBook::where('bible_id', $bible_id)
            ->with(['book' => function ($query) use ($testament) {
                if ($testament) {
                    $query->where('testament', $testament);
                }
            }])
            ->when($book_id, function ($query) use ($book_id) {
                $query->where('book_id', $book_id);
            })
            ->get()->sortBy('book.'.$bible->versification.'_order')->flatten();

        return $this->reply(fractal($books, new BooksTransformer));
    }

    public function edit($id)
    {
        $bible = Bible::with('translations.language')->find($id);
        if (!$this->api) {
            $languages     = Language::select(['iso', 'name'])->orderBy('iso')->get();
            $organizations = OrganizationTranslation::select(['name', 'organization_id'])->where(
                'language_iso',
                'eng'
            )->get();
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
        $organizations = OrganizationTranslation::select(['name', 'organization_id'])->where(
            'language_iso',
            'eng'
        )->get();
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
}
