<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFilesetType;
use App\Models\Organization\Organization;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
use App\Traits\AccessControlAPI;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Serializers\DataArraySerializer;
use App\Http\Controllers\APIController;
use App\Models\Bible\BibleDefault;

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
     *          For a complete list see the `iso` field in the `/languages` route",
     *     ),
     *     @OA\Parameter(
     *          name="organization_id",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The owning organization to return bibles for. For a complete list of ids see the route
     *              `/organizations`."
     *     ),
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The asset_id to filter results by. There are three buckets provided `dbp-prod`, `dbp-vid` & `dbs-web`"
     *     ),
     *     @OA\Parameter(
     *          name="media",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="Will filter bibles based upon the media type of their filesets"
     *     ),
     *     @OA\Parameter(
     *          name="media_exclude",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="Will exclude bibles based upon the media type of their filesets"
     *     ),
     *     @OA\Parameter(
     *          name="size",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="Will filter bibles based upon the size type of their filesets"
     *     ),
     *     @OA\Parameter(
     *          name="bitrate",
     *          in="query",
     *          @OA\Schema(type="string",example="64kps"),
     *          description="Will filter bibles based upon the bitrate of their filesets, the current values available are 16kbps & 64kbps"
     *     ),
     *     @OA\Parameter(
     *          name="size_exclude",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="Will exclude bibles based upon the size type of their filesets"
     *     ),
     *     @OA\Parameter(
     *          name="show_all",
     *          in="query",
     *          @OA\Schema(type="boolean"),
     *          description="Will show all entries"
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible.all")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible.all")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible.all")),
     *         @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_bible.all"))
     *     )
     * )
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $language_code      = checkParam('language_id|language_code');
        $organization_id    = checkParam('organization_id');
        $country            = checkParam('country');
        $asset_id           = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $media              = checkParam('media');
        $media_exclude      = checkParam('media_exclude');
        $size               = checkParam('size');
        $size_exclude       = checkParam('size_exclude');
        $bitrate            = checkParam('bitrate');
        $show_restricted    = checkBoolean('show_all|show_restricted');
        $limit      = checkParam('limit');
        $page       = checkParam('page');

        if ($media) {
            $media_types = BibleFilesetType::select('set_type_code')->get();
            $media_type_exists = $media_types->where('set_type_code', $media);
            if ($media_type_exists->isEmpty()) {
                return $this->setStatusCode(404)->replyWithError('media type not found. must be one of ' . $media_types->pluck('set_type_code')->implode(','));
            }
        }

        $access_control = (!$show_restricted) ? $this->accessControl($this->key) : (object) ['string' => null, 'hashes' => null];
        $organization = $organization_id ? Organization::where('id', $organization_id)->orWhere('slug', $organization_id)->first() : null;
        $cache_string = strtolower('bibles:' . $language_code . $organization . $country . $asset_id . $access_control->string . $media . $media_exclude . $size . $size_exclude . $bitrate . $limit . $page);
        $bibles = \Cache::remember($cache_string, now()->addDay(), function () use ($language_code, $organization, $country, $asset_id, $access_control, $media, $media_exclude, $size, $size_exclude, $bitrate, $show_restricted, $limit, $page) {
            $bibles = Bible::when(!$show_restricted, function ($query) use ($access_control, $asset_id, $media, $media_exclude, $size, $size_exclude, $bitrate) {
                $query->withRequiredFilesets([
                    'access_control' => $access_control,
                    'asset_id'       => $asset_id,
                    'media'          => $media,
                    'media_exclude'  => $media_exclude,
                    'size'           => $size,
                    'size_exclude'   => $size_exclude,
                    'bitrate'        => $bitrate
                ]);
            })
                ->leftJoin('bible_translations as ver_title', function ($join) {
                    $join->on('ver_title.bible_id', '=', 'bibles.id')->where('ver_title.vernacular', 1);
                })
                ->leftJoin('bible_translations as current_title', function ($join) {
                    $join->on('current_title.bible_id', '=', 'bibles.id');
                    if (isset($GLOBALS['i18n_id'])) {
                        $join->where('current_title.language_id', '=', $GLOBALS['i18n_id']);
                    }
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
                        ->orderBy('priority', 'desc');
                    if (isset($GLOBALS['i18n_id'])) {
                        $join->where('language_current.language_translation_id', '=', $GLOBALS['i18n_id']);
                    }
                })
                ->filterByLanguage($language_code)
                ->when($country, function ($q) use ($country) {
                    $q->whereHas('country', function ($query) use ($country) {
                        $query->where('countries.id', $country);
                    });
                })
                ->when($organization, function ($q) use ($organization) {
                    $q->whereHas('organizations', function ($q) use ($organization) {
                        $q->where('organization_id', $organization->id);
                    })->orWhereHas('links', function ($q) use ($organization) {
                        $q->where('organization_id', $organization->id);
                    });
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
                ->orderBy('bibles.priority', 'desc')->groupBy('bibles.id');

            if ($page) {
                $bibles  = $bibles->paginate($limit);
                return $this->reply(fractal($bibles->getCollection(), BibleTransformer::class)->paginateWith(new IlluminatePaginatorAdapter($bibles)));
            }

            $bibles = $bibles->limit($limit)->get();
            return fractal($bibles, new BibleTransformer(), new DataArraySerializer());
        });

        return $this->reply($bibles);
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
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The asset_id to filter results by. There are three buckets provided `dbp-prod`, `dbp-vid` & `dbs-web`"
     *     ),
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The asset_id to filter results by. There are three buckets provided `dbp-prod`, `dbp-vid` & `dbs-web`"
     *     ),
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
        $asset_id = checkParam('asset_id');
        $access_control = $this->accessControl($this->key);
        $cache_string = strtolower('bible_show:' . $id . ':' . $access_control->string);
        $bible = \Cache::remember($cache_string, now()->addDay(), function () use ($access_control, $id) {
            return Bible::with([
                'translations', 'books.book', 'links', 'organizations.logo', 'organizations.logoIcon', 'organizations.translations', 'alphabet.primaryFont', 'equivalents',
                'filesets' => function ($query) use ($access_control) {
                    $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
                }
            ])->find($id);
        });

        if (!$bible || !sizeof($bible->filesets)) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $id]));
        }

        if ($asset_id) {
            $bible->filesets = $bible->filesets->filter(function ($fileset) use ($asset_id) {
                return in_array($fileset->asset_id, explode(',', $asset_id));
            });
        }

        return $this->reply(fractal($bible, new BibleTransformer(), $this->serializer));
    }

    /**
     *
     * @OA\Get(
     *     path="/bibles/{id}/book",
     *     tags={"Bibles"},
     *     summary="Returns a list of translated book names and general information for the given Bible",
     *     description="The actual list of books may vary from fileset to fileset. For example, a King James Fileset may
     *          contain deuterocanonical books that are missing from one of it's sibling filesets nested within the bible
     *          parent.",
     *     operationId="v4_bible.books",
     *     @OA\Parameter(name="id",in="path",required=true,@OA\Schema(ref="#/components/schemas/Bible/properties/id")),
     *     @OA\Parameter(name="book_id",in="query", description="The book id. For a complete list see the `book_id` field in the `/bibles/books` route.",@OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="testament",in="query",@OA\Schema(ref="#/components/schemas/Book/properties/book_testament")),
     *     @OA\Parameter(
     *          name="verify_content",
     *          in="query",
     *          @OA\Schema(type="boolean"),
     *          description="Filter all the books that have content"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible.books")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible.books")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible.books")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_bible.books"))
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

        $asset_id = checkParam('asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $verify_content = checkBoolean('verify_content');

        $bible = Bible::find($bible_id);
        $access_control = $this->accessControl($this->key);
        $cache_string = strtolower('bible_books_bible:' . $bible_id . ':' . $access_control->string . ':' . $verify_content . ':' . $asset_id);
        $bible = \Cache::remember($cache_string, now()->addDay(), function () use ($access_control, $bible_id, $asset_id, $verify_content) {
            if (!$verify_content) {
                return Bible::find($bible_id);
            }

            return  Bible::with([
                'filesets' => function ($query) use ($access_control, $asset_id) {
                    $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
                    if ($asset_id) {
                        $query->whereIn('bible_filesets.asset_id', explode(',', $asset_id));
                    }
                }
            ])->find($bible_id);
        });


        if (!$bible) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $bible_id]));
        }

        $cache_string = strtolower('bible_books_books:' . $bible_id . ':' . $testament . ':' . $book_id);
        $books = \Cache::remember($cache_string, now()->addDay(), function () use ($bible_id, $testament, $book_id, $bible) {
            $books = BibleBook::where('bible_id', $bible_id)
                ->when($testament, function ($query) use ($testament) {
                    $query->where('book_testament', $testament);
                })
                ->when($book_id, function ($query) use ($book_id) {
                    $query->where('book_id', $book_id);
                })
                ->get()->sortBy('book.' . $bible->versification . '_order')
                ->filter(function ($item) {
                    return $item->book;
                })->flatten();
            return $books;
        });

        if ($verify_content) {
            $cache_string = strtolower('bible_books_books_verified:' . $bible_id . ':' . $access_control->string . ':' . $verify_content . ':' . $asset_id . ':' . $testament . ':' . $book_id);
            $books = \Cache::remember($cache_string, now()->addDay(), function () use ($books, $bible) {
                $book_controller = new BooksController();
                $active_books = [];
                foreach ($bible->filesets as $fileset) {
                    $books_fileset = $book_controller->getActiveBooksFromFileset($fileset->id, $fileset->asset_id, $fileset->set_type_code)->pluck('id');
                    $active_books = $this->processActiveBooks($books_fileset, $active_books, $fileset->set_type_code);
                }

                return $books->map(function ($book) use ($active_books) {
                    if (isset($active_books[$book->book_id])) {
                        $book->content_types = array_unique($active_books[$book->book_id]);
                    }
                    return $book;
                })->filter(function ($book) {
                    return $book->content_types;
                });
            });
        }

        return $this->reply(fractal($books, new BooksTransformer));
    }

    private function processActiveBooks($books, $active_books, $set_type_code)
    {
        foreach ($books as $book) {
            $active_books[$book] =  $active_books[$book] ?? [];
            $active_books[$book][] = $set_type_code;
        }
        return $active_books;
    }

    /**
     * @OA\Get(
     *     path="/bibles/defaults/types",
     *     tags={"Bibles"},
     *     summary="Available bible defaults per language code",
     *     description="Available bible defaults per language code",
     *     operationId="v4_bible.defaults",
     *     @OA\Parameter(
     *          name="language_code",
     *          in="query",
     *          @OA\Schema(type="string",example="en"),
     *          description="The language code to filter results by"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bibles_defaults")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bibles_defaults")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bibles_defaults")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_bibles_defaults"))
     *     )
     * )
     *
     * @OA\Schema (
     *    type="array",
     *    schema="v4_bibles_defaults",
     *    description="The bible defaults",
     *    title="v4_bibles_defaults",
     *    @OA\Xml(name="v4_bibles_defaults"),
     *    @OA\Items(
     *          @OA\Property(property="video",             ref="#/components/schemas/BibleFileset/properties/id"),
     *          @OA\Property(property="audio",             ref="#/components/schemas/BibleFileset/properties/id"),
     *          @OA\Property(property="language_code",     type="string"),
     *     )
     *   )
     * )
     *
     */
    public function defaults()
    {
        $language_code = checkParam('language_code');
        $defaults = BibleDefault::when($language_code, function ($q) use ($language_code) {
            $q->where('language_code', $language_code);
        })
            ->get();
        $result = [];
        foreach ($defaults as $default) {
            if (!isset($result[$default->language_code])) {
                $result[$default->language_code] = [];
            }
            $result[$default->language_code][$default->type] = $default->bible_id;
        }
        return $this->reply($result);
    }
}
