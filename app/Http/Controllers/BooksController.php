<?php

namespace App\Http\Controllers;

use App\Models\Bible\Book;
use App\Models\Bible\Text;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BookTranslation;
use App\Transformers\BooksTransformer;

class BooksController extends APIController
{

	/**
	 *
	 * Returns a static list of Scriptural Books and Accompanying meta data
	 *
	 * @version 4
	 * @category v4_bible.allBooks
	 * @link http://api.dbp.dev/bibles/books?key=1234&v=4 - V4 Test Access URL
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Bible/v4_bible_books2 - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/bibles/books/",
	 *     tags={"Version 4"},
	 *     summary="Returns the books of the Bible",
	 *     description="Returns all of the books of the Bible both canonical and deuterocanonical",
	 *     operationId="v4_bible.allBooks",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_bible.allBooks")
	 *         )
	 *     )
	 * )
	 *
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function index()
	{
		if(!$this->api) return view('docs.books');
		return $this->reply(\Cache::remember('v4_books_index', 2400, function() {
			$books = Book::orderBy('book_order')->get();
			return fractal()->collection($books)->transformWith(new BooksTransformer());
		}));
	}


	/**
	 * Gets the book order and code listing for a volume.
	 *
	 * @version 2
	 * @category v2_library_book
	 * @category v2_library_bookOrder
	 * @link http://dbt.io/library/bookorder - V2 Access
	 * @link http://api.dbp.dev/library/bookorder?key=1234&v=2&dam_id=AMKWBT&pretty - V2 Test
	 * @link https://dbp.dev/eng/docs/swagger/v2#/Library/v2_library_book - V2 Test Docs
	 *
	 * @param dam_id - the volume internal bible_id.
	 *
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function show()
    {
		$id = checkParam('dam_id');
	    $bucket_id = checkParam('bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
	    $fileset = BibleFileset::with('bible')->where('id',$id)->where('bucket_id',$bucket_id)->first();
	    if(!$fileset) return $this->setStatusCode(404)->replyWithError("No fileset found for the provided params.");

	    $sophiaTable = $this->checkForSophiaTable($fileset);
	    if(!is_string($sophiaTable)) return $sophiaTable;

		$booksChapters = collect(\DB::connection('sophia')->table($fileset->id.'_vpl')->select('book','chapter')->distinct()->get());
	    $books = Book::whereIn('id_usfx',$booksChapters->pluck('book')->unique()->toArray())->orderBy('book_order')->get();

	    $bible_id = $fileset->bible->first()->id;
	    foreach($books as $key => $book) {
	    	$chapters = $booksChapters->where('book',$book->id_usfx)->pluck('chapter');
	    	$books[$key]->bible_id = $bible_id;
	    	$books[$key]->chapters = $chapters->implode(",");
		    $books[$key]->number_chapters = $chapters->count();
	    }

		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer())->serializeWith($this->serializer));
    }

	/**
	 * Gets the book order and code listing for a volume.
	 *
	 * @version 2
	 * @category v2_library_bookName
	 * @link http://dbt.io/library/bookname - V2 Access
	 * @link http://api.dbp.dev/library/bookname?key=1234&v=2&language_code=ben - V2 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v2#/Library/v2_library_bookname - V2 Test Docs
	 *
	 * @param language_code - The language code to filter the books by
	 *
	 * @return BookTranslation string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function bookNames()
    {
    	if(!$this->api) return view('docs.books.bookNames');

		$languageCode = checkParam('language_code');
		$language = fetchLanguage($languageCode);

		// Fetch Bible Book Names By Bible Iso and Order by Book Order
		return $this->reply(BookTranslation::where('iso',$languageCode)->with('book')->select('name','book_id')->get()->pluck('name','book.id_osis'));
    }

	/**
	 * This lists the chapters for a book or all books in a standard bible volume.
	 *
	 * @version 2
	 * @category v2_library_chapter
	 * @link http://dbt.io/library/chapter - V2 Access
	 * @link https://api.dbp.dev/library/chapter?key=1234&v=2&dam_id=AMKWBT&book_id=MAT&pretty - V2 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v2#/Library/v2_library_chapter - V2 Test Docs
	 *
	 * @param dam_id - The Fileset ID to filter by
	 * @param book_id - The USFM 2.4 or OSIS Book ID code
	 * @param bucket_id - The optional bucket ID of the resource, if not given the API will assume FCBH origin
	 *
	 * @return mixed $chapters string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function chapters()
    {
	    if(!$this->api) return view('docs.books.chapters');

	    $id = checkParam('dam_id');
	    $bucket_id = checkParam('bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
	    $book_id = checkParam('book_id', null, 'optional');

	    $fileset = BibleFileset::with('bible')->where('id',$id)->where('bucket_id',$bucket_id)->first();
	    if(!$fileset) return $this->setStatusCode(404)->replyWithError("No fileset found for the given ID");

	    $book = Book::where('id_osis',$book_id)->orWhere('id',$book_id)->first();
	    if(!$book) return $this->setStatusCode(404)->replyWithError("No book found for the given ID");

	    $sophiaTable = $this->checkForSophiaTable($fileset);
	    if(!is_string($sophiaTable)) return $sophiaTable;

		$chapters = \DB::connection('sophia')->table($fileset->id.'_vpl')
			->when($book, function($q) use ($book) { $q->where('book',$book->id_usfx); })
			->select(['chapter','book'])->distinct()->orderBy('chapter')->get()
			->map(function ($chapter) use ($id, $book) {
				$chapter->book = $book;
				$chapter->bible_id = $id;
				return $chapter;
			});
		return $this->reply(fractal()->collection($chapters)->serializeWith($this->serializer)->transformWith(new BooksTransformer()));
    }

    private function checkForSophiaTable($fileset)
    {
	    $textExists = \Schema::connection('sophia')->hasTable($fileset->id.'_vpl');
	    if(!$textExists) {
		    $fileset->id = substr($fileset->id,0,-4);
		    $textExists = \Schema::connection('sophia')->hasTable($fileset->id.'_vpl');
	    }
	    if(!$textExists) return $this->setStatusCode(404)->replyWithError("The data for this Bible is still being updated, please check back later");
	    return $fileset->id;
    }

}
