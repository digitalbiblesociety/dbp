<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\BibleFile;
use App\Models\Bible\Book;
use App\Models\Bible\Text;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BookTranslation;
use App\Models\Language\Language;
use App\Transformers\BooksTransformer;
use App\Http\Controllers\APIController;
use Illuminate\Http\JsonResponse;

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
	 * @OA\Get(
	 *     path="/bibles/books/",
	 *     tags={"Bibles"},
	 *     summary="Returns the books of the Bible",
	 *     description="Returns all of the books of the Bible both canonical and deuterocanonical",
	 *     operationId="v4_bible.allBooks",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_bible.allBooks")
	 *         )
	 *     )
	 * )
	 *
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function index()
	{
		if (!$this->api) return view('docs.books');
		$books = \Cache::remember('v4_books_index', 2400, function () {
			return fractal()->collection(Book::all())->transformWith(new BooksTransformer());
		});
		return $this->reply($books);
	}

	/**
	 *
	 * Returns the books and chapters for a specific fileset
	 *
	 * @version 4
	 * @category v4_bible.filesets.books
	 * @link https://api.dbp.test/bibles/filesets/TZTWBT/books?key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824&v=4&pretty - V4 Test Access URL
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Bible/v4_bible.filesets_books - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/bibles/filesets/{fileset_id}/books/",
	 *     tags={"Bibles"},
	 *     summary="Returns the books of the Bible",
	 *     description="Returns the books and chapters for a specific fileset",
	 *     operationId="v4_bible.filesets.books",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="fileset_id", in="path", ref="#/components/schemas/BibleFileset/properties/id"),
	 *     @OA\Parameter(name="testament", in="query", description="The testament to filter books by", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="bucket", in="query", description="The bucket to select the fileset by", @OA\Schema(type="string")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_bible.allBooks")
	 *         )
	 *     )
	 * )
	 *
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function show($id)
	{
		$bucket_id = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$testament = checkParam('testament', null, 'optional');

		$fileset   = BibleFileset::with('bible')->where('id', $id)->orWhere('id',substr($id,0,-4))->orWhere('id',substr($id,0,-2))->where('bucket_id', $bucket_id)->first();
		if(!$fileset) return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));

		$bible = $fileset->bible->first();
		if(!$bible) return $this->setStatusCode(404)->replyWithError(trans('api.bible_errors_404', ['id' => $id]));

		// If the bible is stored in the sophia database
		if($fileset->set_type_code == "text_plain") {
			$sophiaTable = $this->checkForSophiaTable($fileset);
			if(is_a($sophiaTable,JsonResponse::class)) return $sophiaTable;
			$booksChapters = collect(\DB::connection('sophia')->table($sophiaTable . '_vpl')->select('book','chapter')->distinct()->get());
			$general_books = Book::whereIn('id_usfx',$booksChapters->pluck('book')->unique()->toArray())->get();
			$books = BookTranslation::with('book')->whereIn('book_id', $general_books->pluck('id'))->where('language_id',$bible->language_id)
						->when($testament, function ($q) use ($testament) {
						    $q->where('book_testament',$testament);
						})->get();

			// Append Chapters to book object
			foreach ($books as $book) $book->chapters = $booksChapters->where('book',$book->book->id_usfx)->pluck('chapter')->toArray();
			$books = $books->sortBy('book.'.$bible->versification.'_order');
		} else {
			// Otherwise select from bible_files table
			$bible_files = BibleFile::where('hash_id',$fileset->hash_id)->select(['book_id','chapter_start'])->distinct()->get();
			$books = BookTranslation::with('book')->whereIn('book_id',$bible_files->pluck('book_id')->unique())->where('language_id',$bible->language_id)->get();

			// Append Chapters to book object
			foreach ($books as $book) $book->chapters = $bible_files->where('book_id',$book->book_id)->pluck('chapter_start')->unique();
			$books = $books->sortBy('book.'.$bible->versification.'_order');
		}

		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer())->serializeWith($this->serializer));
	}

	private function checkForSophiaTable($fileset)
	{
		$textExists = \Schema::connection('sophia')->hasTable(substr($fileset->id, 0, -4) . '_vpl');

		if($textExists)  return substr($fileset->id, 0, -4);
		if(!$textExists) $textExists = \Schema::connection('sophia')->hasTable($fileset->id . '_vpl');
		if(!$textExists) return $this->setStatusCode(404)->replyWithError(trans('api.bible_filesets_errors_checkback', ['id' => $fileset->id]));

		return $fileset->id;
	}

}
