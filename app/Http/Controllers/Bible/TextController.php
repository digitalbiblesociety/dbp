<?php

namespace App\Http\Controllers\Bible;

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
        $access_control_type = (strpos($fileset->set_type_code, 'audio') !== false) ? 'download' : 'api';
        $access_control = $this->accessControl($this->key, $access_control_type);
        if (!\in_array($fileset->hash_id, $access_control->hashes)) {
            return $this->setStatusCode(403)->replyWithError('Your API Key does not have access to this fileset');
        }
        $bible = $fileset->bible->first();

        $book = Book::where('id', $book_id)->orWhere('id_usfx', $book_id)->orWhere('id_osis', $book_id)->first();
        if (!$book) {
            return $this->setStatusCode(422)->replyWithError('Missing or Invalid Book ID');
        }
        $book->push('name_vernacular', $book->translation($bible->language_id));


        // Fetch Verses
        $table = strtoupper($fileset->id) . '_vpl';
        $verses = DB::connection('sophia')->table($table)
                    ->where('book', $book->id_usfx)
                    ->when($verse_start, function ($query) use ($verse_start) {
                        return $query->where('verse_end', '>=', $verse_start);
                    })
                    ->when($chapter, function ($query) use ($chapter) {
                        return $query->where('chapter', $chapter);
                    })
                    ->when($verse_end, function ($query) use ($verse_end) {
                        return $query->where('verse_end', '<=', $verse_end);
                    })
                    ->join(config('database.connections.dbp.database').'.books as books', function ($join) use ($book) {
                        $join->where('books.id', '=', $book->id);
                    })
                    ->join(config('database.connections.dbp.database').'.bible_books as bb', function ($join) use ($bible, $book) {
                        $join->where('bb.book_id', '=', $book->id)
                             ->where('bb.bible_id', '=', $bible->id);
                    })
                    ->join(config('database.connections.dbp.database').'.numeral_system_glyphs as glyph_chapter', function ($join) use ($table, $bible) {
                        $join->on("$table.chapter", '=', 'glyph_chapter.value')
                             ->where('glyph_chapter.numeral_system_id', '=', $bible->numeral_system_id);
                    })
                    ->join(config('database.connections.dbp.database').'.numeral_system_glyphs as glyph_start', function ($join) use ($table, $bible) {
                        $join->on("$table.verse_start", '=', 'glyph_start.value')
                             ->where('glyph_start.numeral_system_id', '=', $bible->numeral_system_id);
                    })
                    ->join(config('database.connections.dbp.database').'.numeral_system_glyphs as glyph_end', function ($join) use ($table, $bible) {
                        $join->on("$table.verse_end", '=', 'glyph_end.value')
                             ->where('glyph_end.numeral_system_id', '=', $bible->numeral_system_id);
                    })
                    ->select([
                        'canon_order',
                        'books.name as book_name',
                        'bb.name as book_vernacular_name',
                        'book as book_id',
                        'books.id_osis as osis_id',
                        'books.protestant_order as protestant_order',
                        'chapter',
                        'verse_start',
                        'verse_end',
                        'verse_text',
                        'glyph_chapter.glyph as chapter_vernacular',
                        'glyph_start.glyph as verse_start_vernacular',
                        'glyph_end.glyph as verse_end_vernacular',
                    ])->get();

        if (\count($verses) === 0) {
            return $this->setStatusCode(404)->replyWithError('No Verses Were found with the provided params');
        }
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
     *     @OA\Parameter(name="id", in="query", description="The numeric ID of the font to retrieve",
     *          @OA\Schema(type="string")),
     *     @OA\Parameter(name="name", in="query", description="Search for a specific font by name",
     *          @OA\Schema(type="string")),
     *     @OA\Parameter(name="platform", in="query", description="Only return fonts that have been authorized for the specified platform. Available values are: `android`, `ios`, `web`, or `all`",
     *          @OA\Schema(type="string",enum={"android","ios","web","all"},default="all")),
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
        $id       = checkParam('id');
        $name     = checkParam('name');

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
        $exclude = checkParam('exclude') ?? false;
        if ($exclude) {
            $exclude = ' -' . $exclude;
        }
        $fileset_id = checkParam('fileset_id');
        $limit    = checkParam('limit') ?? 15;
        $book_id  = checkParam('book|book_id');

        $book = Book::where('id', $book_id)->orWhere('id_usfx', $book_id)->orWhere('id_osis', $book_id)->first();

        $fileset = BibleFileset::with('bible')->where('id', $fileset_id)->orWhere('id', substr($fileset_id, 0, -4))->orWhere('id', substr($fileset_id, 0, -2))->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }
        $bible = $fileset->bible->first();

        $table = strtoupper($fileset->id) . '_vpl';
        $query  = DB::connection('sophia')->getPdo()->quote('+' . str_replace(' ', ' +', $query) . $exclude);
        $verses = DB::connection('sophia')->table($table)
            ->join(config('database.connections.dbp.database').'.books', 'books.id_usfx', 'book')
            ->join(config('database.connections.dbp.database').'.bible_books as bb', function ($join) use ($bible) {
                $join->on('bb.book_id', 'books.id')->where('bible_id', $bible->id);
            })
            ->join(config('database.connections.dbp.database').'.numeral_system_glyphs as glyph_chapter', function ($join) use ($table, $bible) {
                $join->on("$table.chapter", '=', 'glyph_chapter.value')
                     ->where('glyph_chapter.numeral_system_id', '=', $bible->numeral_system_id);
            })
            ->join(config('database.connections.dbp.database').'.numeral_system_glyphs as glyph_start', function ($join) use ($table, $bible) {
                $join->on("$table.verse_start", '=', 'glyph_start.value')
                     ->where('glyph_start.numeral_system_id', '=', $bible->numeral_system_id);
            })
            ->join(config('database.connections.dbp.database').'.numeral_system_glyphs as glyph_end', function ($join) use ($table, $bible) {
                $join->on("$table.verse_end", '=', 'glyph_end.value')
                     ->where('glyph_end.numeral_system_id', '=', $bible->numeral_system_id);
            })
            ->when($book, function ($q) use ($book) {
                $q->whereIn('book', $book->id_usfx);
            })
            ->whereRaw(DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->limit($limit)
            ->select([
                'canon_order',
                'books.name as book_name',
                'books.protestant_order as protestant_order',
                'bb.name as book_vernacular_name',
                'books.id as book_id',
                'chapter',
                'verse_start',
                'verse_end',
                'verse_text',
                'glyph_chapter.glyph as chapter_vernacular',
                'glyph_start.glyph as verse_start_vernacular',
                'glyph_end.glyph as verse_end_vernacular',
            ])->get();

        if ($verses->count() === 0) {
            return $this->setStatusCode(404)->replyWithError('No results found');
        }
        return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer));
    }

    /**
     * This one actually departs from Version 2 and only returns the book ID and the integer count
     *
     * @OA\Get(
     *     path="/text/searchgroup",
     *     tags={"Library Text"},
     *     summary="trans_v2_text_search_group.summary",
     *     description="trans_v2_text_search_group.description",
     *     operationId="v2_text_search_group",
     *     @OA\Parameter(name="query",
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
        // If it's not an API route send them to the documentation
        if (!$this->api) {
            return view('docs.v2.text_search_group');
        }

        $query    = checkParam('query', true);
        $bible_id = checkParam('dam_id');

        $tableExists = \Schema::connection('sophia')->hasTable($bible_id . '_vpl');
        if (!$tableExists) {
            $bible_id    = substr($bible_id, 0, 6);
            $tableExists = \Schema::connection('sophia')->hasTable($bible_id . '_vpl');
        }
        if (!$tableExists) {
            return $this->setStatusCode(404)->replyWithError('Table does not exist');
        }

        $query  = DB::connection('sophia')->getPdo()->quote('+' . str_replace(' ', ' +', $query));
        $verses = DB::connection('sophia')->table($bible_id . '_vpl')->select(DB::raw('MIN(verse_text) as verse_text, COUNT(verse_text) as resultsCount, book, chapter, verse_start, canon_order'))
                    ->whereRaw(DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->orderBy('canon_order')->groupBy('book')->get();

        $books  = Book::with([
            'bibleBooks' => function ($query) use ($bible_id) {
                $query->where('bible_id', $bible_id);
            }])->whereIn('id_usfx', $verses->pluck('book'))->get();

        $verses->map(function ($item) use ($bible_id, $books) {
            $current_book           = $books->where('id_usfx', $item->book)->first();
            $item->book_name        = $current_book->name ?? '';
            $item->id_osis          = $current_book->id_osis ?? '';
            $item->protestant_order = $current_book->protestant_order ?? '';
            $item->bible_id         = $bible_id;
            return $item;
        });

        return $this->reply([
            [['total_results' => $verses->sum('resultsCount')]],
            fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer),
        ]);
    }
}
