<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFilesetType;
use App\Models\Bible\BookTranslation;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
use App\Traits\AccessControlAPI;
use Illuminate\Support\Str;
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
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
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
        $show_restricted    = checkParam('show_restricted');

        if($media) {
            $media_types = BibleFilesetType::select('set_type_code')->get();
            $media_type_exists = $media_types->where('set_type_code', $media);
            if($media_type_exists->isEmpty()) {
                return $this->setStatusCode(404)->replyWithError('media type not found. must be one of ' . $media_types->pluck('set_type_code')->implode(','));
            }
        }

        $access_control = (!$show_restricted) ? $this->accessControl($this->key) : (object) ['string' => null, 'hashes' => null];
        $organization = $organization_id ? Organization::where('id', $organization_id)->orWhere('slug', $organization_id)->first() : null;
        $cache_string = strtolower('bibles:'.$language_code.$organization.$country.$asset_id.$access_control->string.$media.$media_exclude.$size.$size_exclude.$bitrate);
        $bibles = \Cache::remember($cache_string, now()->addDay(), function () use ($language_code, $organization, $country, $asset_id, $access_control, $media, $media_exclude, $size, $size_exclude, $bitrate, $show_restricted) {

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
                    if(isset($GLOBALS['i18n_id'])) {
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
                    if(isset($GLOBALS['i18n_id'])) {
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
                ->orderBy('bibles.priority', 'desc')->groupBy('bibles.id')->get();

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
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $access_control = $this->accessControl($this->key);
        $cache_string = strtolower('bible_show:'.$id.':'.$access_control->string);
        $bible = \Cache::remember($cache_string, now()->addDay(), function() use($access_control,$id) {
            return Bible::with(['translations', 'books.book', 'links', 'organizations.logo','organizations.logoIcon','organizations.translations', 'alphabet.primaryFont','equivalents',
                'filesets' => function ($query) use ($access_control) {
                    $query->whereIn('bible_filesets.hash_id', $access_control->hashes);
                }])->find($id);
        });
        if (!$bible) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $id]));
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
               contain deuterocanonical books that are missing from one of it's sibling filesets nested within the bible
               parent.",
     *     operationId="v4_bible.books",
     *     @OA\Parameter(name="id",in="path",required=true,@OA\Schema(ref="#/components/schemas/Bible/properties/id")),
     *     @OA\Parameter(name="book_id",in="query", description="The book id. For a complete list see the `book_id` field in the `/bibles/books` route.",@OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="testament",in="query",@OA\Schema(ref="#/components/schemas/Book/properties/book_testament")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
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

        $bible = Bible::find($bible_id);
        if(!$bible) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bibles_errors_404', ['bible_id' => $bible_id]));
        }

        $books = BibleBook::where('bible_id', $bible_id)
            ->with(['book' => function ($query) use ($testament) {
                if ($testament) {
                    $query->where('book_testament', $testament);
                }
            }])
            ->when($book_id, function ($query) use ($book_id) {
                $query->where('book_id', $book_id);
            })
            ->get()->sortBy('book.'.$bible->versification.'_order')
            ->filter(function ($item) {
                return $item->book;
            })->flatten();

        return $this->reply(fractal($books, new BooksTransformer));
    }

}
