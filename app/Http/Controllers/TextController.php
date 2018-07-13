<?php

namespace App\Http\Controllers;

use App\Helpers\AWS\Bucket;
use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\Bible\BibleEquivalent;
use App\Models\Language\AlphabetFont;
use App\Traits\AccessControlAPI;
use App\Transformers\FontsTransformer;
use App\Transformers\TextTransformer;
use DB;
use Illuminate\Support\Facades\Storage;

class TextController extends APIController
{

	use AccessControlAPI;

	/**
	 * Display a listing of the Verses
	 * Will either parse the path or query params to get data before passing it to the bible_equivalents table
	 *
	 * @param string|null $bible_url_param
	 * @param string|null $book_url_param
	 * @param string|null $chapter_url_param
	 *
	 * @OAS\Get(
	 *     path="/bibles/{id}/{book}/{chapter}",
	 *     tags={"Bibles"},
	 *     summary="Returns Signed URLs or Text",
	 *     description="V4's base fileset route",
	 *     operationId="v4_bible_filesets.chapter",
	 *     @OAS\Parameter(name="id", in="path", description="The Bible fileset ID", required=true, @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="book", in="path", description="The Book ID", required=true, @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(name="chapter", in="path", description="The chapter number", required=true, @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_chapter"))
	 *     )
	 * )
	 *
	 * @OAS\Get(
	 *     path="/text/verse",
	 *     tags={"Library Text"},
	 *     summary="Returns Signed URLs or Text",
	 *     description="V2's base fileset route",
	 *     operationId="v2_text_verse",
	 *     @OAS\Parameter(name="fileset_id", in="query", description="The Bible fileset ID", required=true, @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="book", in="query", description="The Book ID", required=true, @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(name="chapter", in="query", description="The chapter number", required=true, @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_text_verse")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_text_verse")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v2_text_verse"))
	 *     )
	 * )
	 *
	 * @return JSON|View
	 */
	public function index($bible_url_param = null, $book_url_param = null, $chapter_url_param = null)
	{
		// Fetch and Assign $_GET params
		$fileset_id  = checkParam('dam_id|fileset_id', $bible_url_param);
		$book_id     = checkParam('book_id', $book_url_param);
		$chapter     = checkParam('chapter_id', $chapter_url_param);
		$verse_start = checkParam('verse_start', null, 'optional') ?? 1;
		$verse_end   = checkParam('verse_end', null, 'optional');
		$formatted   = checkParam('bucket|bucket_id', null, 'optional');

		$fileset = BibleFileset::with('bible')->where('id', $fileset_id)->orWhere('id',substr($fileset_id,0,-4))->orWhere('id',substr($fileset_id,0,-2))->first();
		if (!$fileset) return $this->setStatusCode(404)->replyWithError("No fileset found for the provided params");

		$access_control_type = (strpos($fileset->set_type_code, 'audio') !== false) ? "download" : "api";
		$access_control = $this->accessControl($this->key, $access_control_type);
		if(!in_array($fileset->hash_id, $access_control->hashes)) return $this->setStatusCode(403)->replyWithError("Your API Key does not have access to this fileset");
		$bible = $fileset->bible->first();

		$book = Book::where('id', $book_id)->orWhere('id_usfx', $book_id)->orWhere('id_osis', $book_id)->first();
		if (!$book) return $this->setStatusCode(422)->replyWithError('Missing or Invalid Book ID');
		$book->push('name_vernacular', $book->translation($bible->language_id)->first());

		if ($formatted) {
			$path   = 'text/' . $bible->id . '/' . $fileset->id . '/' . $book_id . $chapter . '.html';
			$exists = Storage::disk($formatted)->exists($path);
			if (!$exists) return $this->replyWithError("The path: $path did not result in a file");

			return $this->reply(["path" => Bucket::signedUrl($path)], [], true);
		}

		// Fetch Verses
		$verses = DB::connection('sophia')->table(strtoupper($fileset->id) . '_vpl')
		            ->where([['book', $book->id_usfx], ['chapter', $chapter]])
		            ->when($verse_start, function ($query) use ($verse_start) {
			            return $query->where('verse_end', '>=', $verse_start);
		            })
		            ->when($verse_end, function ($query) use ($verse_end) {
			            return $query->where('verse_end', '<=', $verse_end);
		            })->get();
		$this->addMetaDataToVerses($verses, $bible->id);

		if (count($verses) == 0) {
			return $this->setStatusCode(404)->replyWithError("No Verses Were found with the provided params");
		}

		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer)->toArray());
	}

	public function formattedResponse()
	{
		$bible_id = checkParam('dam_id|fileset_id');
		$book_id  = checkParam('book_id');
		$chapter  = checkParam('chapter_id');

		$url     = "https://s3-us-west-2.amazonaws.com/dbp-dev/text/" . $bible_id . "/" . $bible_id . "/" . $book_id . $chapter . ".html";
		$chapter = Bucket::get($url);
	}

	/**
	 * Display a listing of the Fonts
	 *
	 * @OAS\Get(
	 *     path="/text/font",
	 *     tags={"Library Text"},
	 *     summary="Returns utilized fonts",
	 *     description="Some languages used by the Digital Bible Platform utilize character sets that are not supported by `standard` fonts. This call provides a list of custom fonts that have been made available.",
	 *     operationId="v2_text_font",
	 *     @OAS\Parameter(name="id", in="query", description="The numeric ID of the font to retrieve",
	 *          @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="name", in="query", description="Search for a specific font by name",
	 *          @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="platform", in="query", description="Only return fonts that have been authorized for the specified platform. Available values are: `android`, `ios`, `web`, or `all`",
	 *          @OAS\Schema(type="string",enum={"android","ios","web","all"},default="all")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/font_response")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/font_response")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/font_response"))
	 *     )
	 * )
	 *
	 * @return JSON|View
	 */
	public function fonts()
	{
		$id       = checkParam('id', null, 'optional');
		$name     = checkParam('name', null, 'optional');
		$platform = checkParam('platform', null, 'optional') ?? 'all';

		$fonts = AlphabetFont::when($name, function ($q) use($name) {
			$q->where('name', $name);
		})->when($name, function ($q) use($id) {
			$q->where('id', $id);
		})->get();

		return $this->reply(fractal()->collection($fonts)->transformWith(new FontsTransformer())->serializeWith($this->serializer)->toArray());
	}

	/**
	 *
	 * @OAS\Get(
	 *     path="/search",
	 *     tags={"Bibles"},
	 *     summary="Run a text search on a specific fileset",
	 *     description="",
	 *     operationId="v4_text_search",
	 *     @OAS\Parameter(name="fileset_id", in="query", description="The Bible fileset ID", required=true,
	 *          @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="limit",  in="query", description="The number of search results to return",
	 *          @OAS\Schema(type="integer",example=15,default=15)),
	 *     @OAS\Parameter(name="books",  in="query", description="The Books to search through",
	 *          @OAS\Schema(type="string",example="GEN,EXO,MAT")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_chapter")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_chapter"))
	 *     )
	 * )
	 *
	 * @return View|JSON
	 */
	public function search()
	{
		// If it's not an API route send them to the documentation
		if (!$this->api) return view('docs.v2.text_search');

		$query   = checkParam('query');
		$exclude = checkParam('exclude', null, 'optional') ?? false;
		if ($exclude) $exclude = ' -' . $exclude;
		$bible_id = checkParam('dam_id');
		$limit    = checkParam('limit', null, 'optional') ?? 15;
		$books    = checkParam('books', null, 'optional');

		$query  = DB::connection('sophia')->getPdo()->quote('+' . str_replace(' ', ' +', $query) . $exclude);
		$verses = DB::connection('sophia')->table(strtoupper($bible_id) . '_vpl')
		            ->whereRaw(DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->limit($limit)
		            ->when($books, function ($q) use ($books) {
			            $q->whereIn('book', explode(',', $books));
		            })->get();
		if($verses->count() == 0) return $this->setStatusCode(404)->replyWithError("No results found");

		$this->addMetaDataToVerses($verses, $bible_id);

		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer));
	}

	/**
	 * This one actually departs from Version 2 and only returns the book ID and the integer count
	 *
	 * @OAS\Get(
	 *     path="/text/searchgroup",
	 *     tags={"Library Text"},
	 *     summary="trans_v2_text_search_group.summary",
	 *     description="trans_v2_text_search_group.description",
	 *     operationId="v2_text_search_group",
	 *     @OAS\Parameter(name="query",
	 *          in="query",
	 *          description="trans_v2_text_search_group.param_query",
	 *          required=true,
	 *          @OAS\Schema(type="integer")
	 *     ),
	 *     @OAS\Parameter(
	 *          name="dam_id",
	 *          in="query",
	 *          description="trans_v2_text_search_group.param_dam_id",
	 *          required=true,
	 *          @OAS\Schema(type="string")
	 *     ),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_text_search_group")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_text_search_group")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v2_text_search_group"))
	 *     )
	 * )
	 *
	 * @return View|JSON
	 */
	public function searchGroup()
	{
		// If it's not an API route send them to the documentation
		if (!$this->api) return view('docs.v2.text_search_group');

		$query    = checkParam('query');
		$bible_id = checkParam('dam_id');

		$tableExists = \Schema::connection('sophia')->hasTable($bible_id . '_vpl');
		if (!$tableExists) {
			$bible_id    = substr($bible_id, 0, 6);
			$tableExists = \Schema::connection('sophia')->hasTable($bible_id . '_vpl');
		}
		if (!$tableExists) return $this->setStatusCode(404)->replyWithError("Table does not exist");

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

	public function addMetaDataToVerses($verses, $bible_id)
	{
		$usfm  = false;
		$books = Book::whereIn('id_usfx', $verses->pluck('book'))->get();
		if ($books->isEmpty()) {
			$usfm  = true;
			$books = Book::whereIn('id', $verses->pluck('book'))->get();
		}

		$vernacular_numbers = null;

		// Fetch Bible for Book Translations
		$bibleEquivalent = BibleEquivalent::where('equivalent_id', $bible_id)->orWhere('equivalent_id',
			substr($bible_id, 0, 7))->first();
		if (!isset($bibleEquivalent)) $bible = Bible::find($bible_id);
		if (isset($bibleEquivalent) AND !isset($bible)) $bible = $bibleEquivalent->bible;

		// Fetch Vernacular Number
		$verses->map(function ($verse) use ($books, $bible_id, $vernacular_numbers) {
			$currentBook = $books->where('id_usfx', $verse->book)->first();

			$verse->bible_id               = $bible_id;
			$verse->usfm_id                = @$currentBook->id;
			$verse->osis_id                = $currentBook->id_osis;
			$verse->protestant_order       = $currentBook->protestant_order;
			$verse->book_vernacular_name   = @$currentBook->name;
			$verse->book_name              = @$currentBook->name;
			$verse->chapter_vernacular     = isset($vernacular_numbers[$verse->chapter]) ? $vernacular_numbers[$verse->chapter] : $verse->chapter;
			$verse->verse_start_vernacular = isset($vernacular_numbers[$verse->chapter]) ? $vernacular_numbers[$verse->verse_start] : $verse->verse_start;
			$verse->verse_end_vernacular   = isset($vernacular_numbers[$verse->chapter]) ? $vernacular_numbers[$verse->verse_end] : $verse->verse_end;

			return $verse;
		});

		return $verses;
	}


}
