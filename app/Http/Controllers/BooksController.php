<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleBook;
use App\Models\Bible\Book;
use App\Models\Bible\Text;
use App\Transformers\BooksTransformer;

class BooksController extends APIController
{

	/**
	 *
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
	{
		if(!$this->api) return view('docs.books');
		return \Cache::remember('v4_books_index', 2400, function() {
			$books = Book::with('codes')->orderBy('book_order')->get();
			return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer()));
		});
	}


	/**
	 * This Function handles the "Book Order Listing" Route on V2 and the "books" route on V4
	 * Gets the book order and code listing for a volume.
	 * REST URL: http://dbt.io/library/bookorder
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show()
    {
    	if(!$this->api) return view('docs.v2.books.BookOrderListing');

		$abbreviation = checkParam('dam_id');
		$books = BibleBook::with('book')->where('bible_id',$abbreviation)->get()->sortBy('book.book_order');
		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer())->serializeWith($this->serializer)->toArray());
    }

	/**
	 * This function handles the "Book Name Listing" route on Version 2 of the DBP
	 * This will retrieve the native language book names for a DBP language code.
	 * OLD REST URL: http://dbt.io/library/bookname
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function bookNames()
    {
    	if(!$this->api) return view('docs.books.bookNames');

		$languageCode = checkParam('language_code');
		$language = fetchLanguage($languageCode);

		// Fetch Bible Book Names By Bible Iso and Order by Book Order
		$books = BibleBook::whereHas('bible', function($q) use ($language) {$q->where('iso', '=', $language->iso);})
			->select(['book_id','bible_books.name','books.book_order'])
			->join('books', 'books.id', '=', 'bible_books.book_id')
			->orderBy('books.book_order', 'ASC')->distinct()->get();

		return $this->reply(fractal()->collection($books)->serializeWith($this->serializer)->transformWith(new BooksTransformer()));
    }

	/**
	 * Supports V2:
	 *
	 * This Function handles the "Chapter Listing" route on Version 2 of the DBP
	 * This lists the chapters for a book or all books in a standard bible volume.
	 * Story volumes in DBP are defined in the same top down fashion as standard bibles.
	 * So the first partitioning is into books, which correspond to the segments of audio or video.
	 * So story volumes have no chapters.
	 * OLD REST URL: http://dbt.io/library/chapter
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function chapters()
    {
	    if(!$this->api) return view('docs.books.chapters');

		$bible_id = checkParam('dam_id');
		$book_id = checkParam('book_id', null, true);

		$chapters = Text::where('bible_id',$bible_id)->when($book_id, function ($query) use ($book_id) {
			return $query->where('book_id',$book_id);
		})->select(['chapter_number','bible_id','book_id'])->distinct()->orderBy('chapter_number')->get();
		
		return $this->reply(fractal()->collection($chapters)->serializeWith($this->serializer)->transformWith(new BooksTransformer()));
    }

}
