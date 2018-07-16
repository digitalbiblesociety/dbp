<?php

namespace App\Http\Controllers;

use App\Models\Bible\Book;
use App\Models\Bible\Text;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BookTranslation;
use App\Models\Language\Language;
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
	 *     tags={"Bibles"},
	 *     summary="Returns the books of the Bible",
	 *     description="Returns all of the books of the Bible both canonical and deuterocanonical",
	 *     operationId="v4_bible.allBooks",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/v4_bible.allBooks")
	 *         )
	 *     )
	 * )
	 *
	 * @return Book string - A JSON string that contains the status code and error messages if applicable.
	 */
	public function index()
	{
		if (!$this->api) {
			return view('docs.books');
		}
		$books = \Cache::remember('v4_books_index', 2400, function () {
			return fractal()->collection(Book::all())->transformWith(new BooksTransformer());
		});

		return $this->reply($books);
	}

	public function show()
	{
		$id        = checkParam('dam_id');
		$bucket_id = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');

		$fileset   = BibleFileset::with('bible')->where('id', $id)->orWhere('id',substr($id,0,-4))->orWhere('id',substr($id,0,-2))->where('bucket_id', $bucket_id)->first();
		if (!$fileset) return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));

		$sophiaTable = $this->checkForSophiaTable($fileset);
		if (!is_string($sophiaTable)) return $sophiaTable;

		$testament = false;

		switch (substr($id, -2, 1)) {
			case "O": {
				$testament = "OT";
				break;
			}
			case "N": {
				$testament = "NT";
			}
		}

		$libraryBook = \Cache::remember('v2_library_book_' . $id . $bucket_id . $fileset . $testament, 1600,
			function () use ($id, $bucket_id, $fileset, $testament, $sophiaTable) {
				$booksChapters = collect(\DB::connection('sophia')->table($sophiaTable . '_vpl')->select('book','chapter')->distinct()->get());
				$books = Book::whereIn('id_usfx', $booksChapters->pluck('book')->unique()->toArray())
					->when($testament, function ($q) use ($testament) {
				             $q->where('book_testament',$testament);
					})->orderBy('protestant_order')->get();
				
				$bible_id = $fileset->bible->first()->id;
				foreach ($books as $key => $book) {
					$chapters                     = $booksChapters->where('book', $book->id_usfx)->pluck('chapter');
					$books[$key]->source_id       = $id;
					$books[$key]->bible_id        = $bible_id;
					$books[$key]->chapters        = $chapters->implode(",");
					$books[$key]->number_chapters = $chapters->count();
				}

				return fractal()->collection($books)->transformWith(new BooksTransformer())->serializeWith($this->serializer);
			});

		return $this->reply($libraryBook);
	}

	private function checkForSophiaTable($fileset)
	{
		$textExists = \Schema::connection('sophia')->hasTable(substr($fileset->id, 0, -4) . '_vpl');
		if ($textExists) {
			return substr($fileset->id, 0, -4);
		}
		if (!$textExists) {
			$textExists = \Schema::connection('sophia')->hasTable($fileset->id . '_vpl');
		}
		if (!$textExists) {
			return $this->setStatusCode(404)->replyWithError(trans('api.bible_filesets_errors_checkback',
				['id' => $fileset->id]));
		}

		return $fileset->id;
	}

}
