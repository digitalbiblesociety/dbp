<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Language\Language;
use App\Transformers\BooksTransformer;

class BooksController extends APIController
{

	/**
	 *
	 *
	 *
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show()
    {
	    $abbreviation = checkParam('dam_id');
    	$books = BibleBook::with('book')->where('abbr',$abbreviation)->get()->sortBy('book.book_order');
    	if($this->api) return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer()));
    	return view('docs.books.show');
    }

	/**
	 *
	 *
	 * @return array|mixed
	 */
	public function bookNames()
    {
    	$languageCode = checkParam('language_code');
	    $language = Language::fetchByID($languageCode);

    	$bibles = Bible::with('books')->where('glotto_Id',$language->id)->get()->pluck('books','abbr');
    	$chosenBook = collect($bibles)->max()->first()->abbr;
		$books = $bibles[$chosenBook]->pluck('name','book_id');
	    if($this->api) return $this->reply([$books]);
    }

    public function chapters()
    {
	    if(!isset($_GET['dam_id'])) return ["error" => "DAM_ID is Required"];
	    $abbreviation = $_GET['dam_id'];
	    $books = BibleBook::with('book')->where('abbr',$abbreviation)->get()->sortBy('book.book_order');
	    if($this->api) return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer()));
    }

}
