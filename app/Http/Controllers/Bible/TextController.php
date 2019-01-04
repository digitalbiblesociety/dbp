<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\BibleVerse;
use DB;

use Illuminate\Http\Response;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\Language\AlphabetFont;
use App\Traits\AccessControlAPI;
use App\Traits\CallsBucketsTrait;
use App\Transformers\FontsTransformer;
use App\Transformers\TextTransformer;
use App\Http\Controllers\APIController;

class TextController extends APIController
{
    use CallsBucketsTrait;
    use AccessControlAPI;

    /**
     * Display a listing of the Verses
     * Will either parse the path or query params to get data before passing it to the bible_equivalents table
     *
     * @param string|null $bible_url_param
     * @param string|null $book_url_param
     * @param string|null $chapter_url_param
     *
     * @OA\Get(
     *     path="/bibles/{id}/{book}/{chapter}",
     *     tags={"Bibles"},
     *     summary="Returns Signed URLs or Text",
     *     description="V4's base fileset route",
     *     operationId="v4_bible_filesets.chapter",
     *     @OA\Parameter(name="id", in="path", description="The Bible fileset ID", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="book", in="path", description="The Book ID", required=true, @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="chapter", in="path", description="The chapter number", required=true, @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible_filesets_chapter"))
     *     )
     * )
     *
     * @OA\Get(
     *     path="/text/verse",
     *     tags={"Library Text"},
     *     summary="Returns Signed URLs or Text",
     *     description="V2's base fileset route",
     *     operationId="v2_text_verse",
     *     @OA\Parameter(name="fileset_id", in="query", description="The Bible fileset ID", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="book", in="query", description="The Book ID", required=true, @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="chapter", in="query", description="The chapter number", required=true, @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_text_verse")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_text_verse")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v2_text_verse"))
     *     )
     * )
     *
     * @return Response
     */
    public function index($bible_url_param = null, $book_url_param = null, $chapter_url_param = null)
    {
        // Fetch and Assign $_GET params
        $fileset_id  = checkParam('dam_id|fileset_id', true, $bible_url_param);
        $book_id     = checkParam('book_id', true, $book_url_param);
        $chapter     = checkParam('chapter_id', true, $chapter_url_param);
        $verse_start = checkParam('verse_start') ?? 1;
        $verse_end   = checkParam('verse_end');
        $asset_id    = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3.bucket');

        $fileset = BibleFileset::with('bible')->uniqueFileset($fileset_id, $asset_id, 'text_plain')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }
        $bible = optional($fileset->bible)->first();

        $access_control = $this->accessControl($this->key);
        if (!\in_array($fileset->hash_id, $access_control->hashes)) {
            return $this->setStatusCode(403)->replyWithError('Your API Key does not have access to this fileset');
        }

        $cache_string = strtolower('bible_text'.$fileset_id.$book_id.$chapter.$verse_start.$verse_end.$asset_id);
        $verses = \Cache::remember($cache_string, 2400, function() use ($fileset,$bible,$book_id,$chapter,$verse_start,$verse_end) {
           return BibleVerse::withVernacularMetaData($bible)
                ->where('hash_id', $fileset->hash_id)
                ->where('bible_verses.book_id', $book_id)
                ->when($verse_start, function ($query) use ($verse_start) {
                    return $query->where('verse_end', '>=', $verse_start);
                })
                ->when($chapter, function ($query) use ($chapter) {
                    return $query->where('chapter', $chapter);
                })
                ->when($verse_end, function ($query) use ($verse_end) {
                    return $query->where('verse_end', '<=', $verse_end);
                })
                ->orderBy('verse_start')
                ->select([
                    'bible_verses.book_id as book_id',
                    'books.name as book_name',
                    'bible_books.name as book_vernacular_name',
                    'bible_verses.chapter',
                    'bible_verses.verse_start',
                    'bible_verses.verse_end',
                    'bible_verses.verse_text',
                    'glyph_chapter.glyph as chapter_vernacular',
                    'glyph_start.glyph as verse_start_vernacular',
                    'glyph_end.glyph as verse_end_vernacular',
                ])->get();
        });

        return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer)->toArray());
    }

    /**
     * Display a listing of the Fonts
     *
     * @OA\Get(
     *     path="/text/font",
     *     tags={"Library Text"},
     *     summary="Returns utilized fonts",
     *     description="Some languages used by the Digital Bible Platform utilize character sets that are not supported by `standard` fonts. This call provides a list of custom fonts that have been made available.",
     *     operationId="v2_text_font",
     *     @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="The numeric ID of the font to retrieve",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Search for a specific font by name",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="platform",
     *          in="query",
     *          description="Only return fonts that have been authorized for the specified platform. Available values are: `android`, `ios`, `web`, or `all`. All the current fonts are available cross-platform",
     *          @OA\Schema(type="string",enum={"android","ios","web","all"},default="all")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/font_response")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/font_response")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/font_response"))
     *     )
     * )
     *
     * @return Response
     */
    public function fonts()
    {
        $id   = checkParam('id');
        $name = checkParam('name');

        $fonts = AlphabetFont::when($name, function ($q) use ($name) {
            $q->where('name', $name);
        })->when($name, function ($q) use ($id) {
            $q->where('id', $id);
        })->get();

        return $this->reply(fractal()->collection($fonts)->transformWith(new FontsTransformer())->serializeWith($this->serializer)->toArray());
    }

    /**
     *
     * @OA\Get(
     *     path="/search",
     *     tags={"Bibles"},
     *     summary="Run a text search on a specific fileset",
     *     description="",
     *     operationId="v4_text_search",
     *     @OA\Parameter(name="fileset_id", in="query", description="The Bible fileset ID", required=true,
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="limit",  in="query", description="The number of search results to return",
     *          @OA\Schema(type="integer",example=15,default=15)),
     *     @OA\Parameter(name="books",  in="query", description="The Books to search through",
     *          @OA\Schema(type="string",example="GEN,EXO,MAT")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible_filesets_chapter"))
     *     )
     * )
     *
     * @return Response
     */
    public function search()
    {
        // If it's not an API route send them to the documentation
        if (!$this->api) {
            return view('docs.v2.text_search');
        }

        $query   = checkParam('query', true);
        $fileset_id = checkParam('fileset_id|dam_id', true);
        $limit    = checkParam('limit') ?? 15;
        $book_id  = checkParam('book|book_id');
        $asset_id = checkParam('asset_id') ?? 'dbp-prod';

        $fileset = BibleFileset::with('bible')->where('id', $fileset_id)->where('set_type_code', 'text_plain')
                                                                        ->where('asset_id', $asset_id)->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }
        $bible = $fileset->bible->first();

        $verses = BibleVerse::where('hash_id', $fileset->hash_id)
            ->withVernacularMetaData($bible)
            ->when($book_id, function ($query) use ($book_id) {
                $query->where('book_id', $book_id);
            })
            ->whereRaw(DB::raw("MATCH (verse_text) AGAINST(\"$query\" IN NATURAL LANGUAGE MODE)"))
            ->select([
                'bible_verses.book_id as book_id',
                'books.name as book_name',
                'bible_books.name as book_vernacular_name',
                'bible_verses.chapter',
                'bible_verses.verse_start',
                'bible_verses.verse_end',
                'bible_verses.verse_text',
                'glyph_chapter.glyph as chapter_vernacular',
                'glyph_start.glyph as verse_start_vernacular',
                'glyph_end.glyph as verse_end_vernacular',
            ])->limit($limit)->get();

        return $this->reply(fractal($verses, new TextTransformer(), $this->serializer));
    }

    /**
     *
     * @OA\Get(
     *     path="/text/searchgroup",
     *     tags={"Library Text"},
     *     summary="trans_v2_text_search_group.summary",
     *     description="trans_v2_text_search_group.description",
     *     operationId="v2_text_search_group",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          description="trans_v2_text_search_group.param_query",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="dam_id",
     *          in="query",
     *          description="trans_v2_text_search_group.param_dam_id",
     *          required=true,
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_text_search_group")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_text_search_group")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v2_text_search_group"))
     *     )
     * )
     *
     * @return Response
     */
    public function searchGroup()
    {
        $query      = checkParam('query', true);
        $fileset_id = checkParam('dam_id');
        $asset_id   = checkParam('asset_id') ?? config('filesystems.disks.s3.bucket');

        $hash_id = optional(BibleFileset::with('bible')->where('id', $fileset_id)
            ->where('set_type_code', 'text_plain')->where('asset_id', $asset_id)
            ->select('hash_id')->first())->hash_id;
        if (!$hash_id) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        $verses = \DB::connection('dbp')->table('bible_verses')
            ->where('bible_verses.hash_id', $hash_id)
            ->join('bible_filesets', 'bible_filesets.hash_id', 'bible_verses.hash_id')
            ->join('books', 'bible_verses.book_id', 'books.id')
            ->select(
                DB::raw(
                   'MIN(verse_text) as verse_text,
                    MIN(verse_start) as verse_start,
                    COUNT(verse_text) as resultsCount,
                    MIN(verse_start),
                    MIN(chapter) as chapter,
                    MIN(bible_filesets.id) as bible_id,
                    MIN(books.id_usfx) as book_id,
                    MIN(books.name) as book_name,
                    MIN(books.protestant_order) as protestant_order'
                )
            )
            ->whereRaw(DB::raw("MATCH (verse_text) AGAINST(\"$query\" IN NATURAL LANGUAGE MODE)"))
            ->groupBy('book_id')->orderBy('protestant_order')->get();

        return $this->reply([
            [
                ['total_results' => $verses->sum('resultsCount')]
            ],
            fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer),
        ]);
    }

    /**
     * This function handles the library/verseinfo route
     * for backwards compatibility with v2. Lacking a
     * transformer as it's essentially depreciated
     *
     *
     * @version 2
     * @category v2_library_book
     * @category v2_library_bookOrder
     * @link https://dbt.io/library/verseinfo?key=TEST_KEY&v=2&dam_id=ENGKJV&book_id=GEN&chapter=1&verse_start=11 - V2 Access
     * @link https://api.dbp.test/library/verseinfo?key=TEST_KEY&v=2&dam_id=ENGKJV&book_id=GEN&chapter=1&verse_start=11 - V2 Test
     * @link https://dbp.test/eng/docs/swagger/v2#/Library/v2_library_verseinfo - V2 Test Docs
     *
     * @OA\Get(
     *     path="/library/verseinfo",
     *     tags={"Library Catalog"},
     *     summary="Returns Library File path information",
     *     description="This method retrieves the bible verse info for the specified volume/book/chapter.",
     *     operationId="v2_library_verseinfo",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(
     *          name="dam_id",
     *          in="query",
     *          required=true,
     *          description="the DAM ID of the verse info",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(
     *          name="book_id",
     *          in="path",
     *          description="If specified returns verse text ONLY for the specified book",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")
     *     ),
     *     @OA\Parameter(
     *          name="chapter",
     *          in="path",
     *          description=" If specified returns verse text ONLY for the specified chapter",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")
     *     ),
     *     @OA\Parameter(
     *          name="verse_start",
     *          in="path",
     *          description="Returns all verse text for the specified book, chapter, and verse range from 'verse_start' until either the end of chapter or 'verse_end'",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/verse_start")
     *     ),
     *     @OA\Parameter(
     *          name="verse_end",
     *          in="path",
     *          description="If specified returns of all verse text for the specified book, chapter, and verse range from 'verse_start' to 'verse_end'.",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/verse_end")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_asset")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/v2_library_asset"))
     *     )
     * )
     *
     * @return mixed
     */
    public function info()
    {
        $bible_id    = checkParam('dam_id|bible_id', true);
        $book_id     = checkParam('book_id');
        $chapter_id  = checkParam('chapter|chapter_id');
        $verse_start = checkParam('verse_start') ?? 1;
        $verse_end   = checkParam('verse_end');
        $asset_id    = checkParam('asset_id') ?? config('filesystems.disks.s3.bucket');

        $fileset = BibleFileset::where([['id', $bible_id], ['asset_id', $asset_id], ['set_type_code', 'text_plain']])->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError(trans('api.fileset_errors_404'));
        }

        $verse_info = BibleVerse::where('hash_id', $fileset->hash_id)->where([
            ['book_id', '=', $book_id],
            ['chapter', '=', $chapter_id],
            ['verse_start', '>=', $verse_start],
        ])->when($verse_end, function ($query) use ($verse_end) {
            return $query->where('verse_start', '<=', $verse_end);
        })->select(['book_id', 'chapter as chapter_number', 'verse_start', 'verse_end', 'verse_text'])->get();

        foreach ($verse_info as $key => $verse) {
            $verse_info[$key]->bible_id           = $fileset->id;
            $verse_info[$key]->bible_variation_id = null;
        }

        /**
         * @OA\Schema (
         *   type="array",
         *   schema="v2_library_verseInfo",
         *   description="The v2_audio_timestamps response",
         *   title="v2_library_verseInfo",
         *   @OA\Xml(name="v2_library_verseInfo"),
         *   @OA\Items(
         *     @OA\Property(property="book_id",        ref="#/components/schemas/BibleVerse/properties/book_id"),
         *     @OA\Property(property="chapter_number", ref="#/components/schemas/BibleVerse/properties/chapter"),
         *     @OA\Property(property="verse_start",    ref="#/components/schemas/BibleVerse/properties/verse_number"),
         *     @OA\Property(property="verse_end",      @OA\Schema(type="integer")),
         *     @OA\Property(property="verse_text",     ref="#/components/schemas/BibleVerse/properties/verse_text"),
         *     )
         *   )
         * )
         */
        return $this->reply($verse_info);
    }

}
