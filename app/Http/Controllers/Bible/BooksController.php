<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFile;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFileset;
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
	 * @category v4_bible_books_all
	 * @link http://api.dbp.test/bibles/books?key=1234&v=4 - V4 Test Access URL
	 * @link https://dbp.test/eng/docs/swagger/v4#/Bible/v4_bible_books2 - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/bibles/books/",
	 *     tags={"Bibles"},
	 *     summary="Returns the books of the Bible",
	 *     description="Returns all of the books of the Bible both canonical and deuterocanonical",
	 *     operationId="v4_bible_books_all",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_bible_books_all")
	 *         )
	 *     )
	 * )
	 *
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function index()
	{
		if (!$this->api) return view('docs.books');
		if(config('app.env') === 'local') \Cache::forget('v4_books_index');
		$books = \Cache::remember('v4_books_index', 2400, function () {
			$books = Book::orderBy('protestant_order')->get();
			return fractal($books,new BooksTransformer(),$this->serializer);
		});
		return $this->reply($books);
	}

	/**
	 *
	 * Returns the books and chapters for a specific fileset
	 *
	 * @version  4
	 * @category v4_bible_filesets.books
	 * @link     https://api.dbp.test/bibles/filesets/TZTWBT/books?key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824&v=4&pretty - V4 Test Access URL
	 * @link     https://dbp.test/eng/docs/swagger/v4#/Bible/v4_bible_filesets.books - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/bibles/filesets/{fileset_id}/books/",
	 *     tags={"Bibles"},
	 *     summary="Returns the books of the Bible",
	 *     description="Returns the books and chapters for a specific fileset",
	 *     operationId="v4_bible_filesets.books",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="fileset_id", in="path", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="fileset_type", in="query", description="The type of fileset being queried", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="testament", in="query", description="The testament to filter books by", @OA\Schema(type="string")),
	 *     @OA\Parameter(name="asset_id", in="query", description="The asset id to select the fileset by", @OA\Schema(type="string")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/v4_bible_books_all")
	 *         )
	 *     )
	 * )
	 *
	 * @param $id
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function show($id)
	{
		$fileset_type = checkParam('fileset_type');

		$asset_id = checkParam('bucket|bucket_id|asset_id', null, 'optional') ?? config('filesystems.disks.s3_fcbh.bucket');
		$testament = checkParam('testament', null, 'optional');

		$fileset   = BibleFileset::with('bible')->where('id', $id)->where('asset_id', $asset_id)
		                         ->where('set_type_code',$fileset_type)->first();
		if(!$fileset) return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));

		$bible = $fileset->bible->first();
		if(!$bible) return $this->setStatusCode(404)->replyWithError(trans('api.bible_errors_404', ['id' => $id]));

		// If the bible is stored in the sophia database
		if($fileset->set_type_code === 'text_plain') {
			$sophiaTable = $this->checkForSophiaTable($fileset);
			if(is_a($sophiaTable,JsonResponse::class)) return $sophiaTable;
			$booksChapters = collect(\DB::connection('sophia')->table($sophiaTable . '_vpl')->select('book','chapter')->distinct()->get());
			$general_books = Book::whereIn('id_usfx',$booksChapters->pluck('book')->unique()->toArray())->get();

			$books = BibleBook::with('book')
			            ->whereIn('book_id', $general_books->pluck('id'))
			            ->where('bible_id',$bible->id)
						->when($testament, function ($q) use ($testament) {
							$q->where('book_testament',$testament);
						})->get();

			// Append Chapters to book object
			foreach ($books as $book) $book->chapters = $booksChapters->where('book',$book->book->id_usfx)->pluck('chapter')->toArray();
			$books = $books->sortBy('book.'.$bible->versification.'_order');
		} else {
			// Otherwise select from bible_files table
			if ($fileset->hash_id === null) return $this->setStatusCode(404)->replyWithError('Fileset Exists but is not ready for public use');
			$bible_files = BibleFile::where('hash_id',$fileset->hash_id)->select(['book_id','chapter_start'])->distinct()->get();
			$books = BibleBook::with('book')
			                  ->whereIn('book_id', $bible_files->pluck('book_id')->unique())
			                  ->where('bible_id',$bible->id)
			                  ->when($testament, function ($q) use ($testament) {
				                  $q->where('book_testament',$testament);
			                  })->get();

			// Append Chapters to book object
			foreach ($books as $book) $book->chapters = $bible_files->where('book_id',$book->book_id)->pluck('chapter_start')->unique();
			$books = $books->sortBy('book.'.$bible->versification.'_order');
		}

		return $this->reply(fractal($books,new BooksTransformer(),$this->serializer));
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
