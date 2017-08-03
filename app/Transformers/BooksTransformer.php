<?php

namespace App\Transformers;

use App\Models\Bible\Book;
use League\Fractal\TransformerAbstract;

class BooksTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = $_GET['v'] ?? 4;
		$this->iso = $_GET['iso'] ?? "eng";
	}

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($book)
    {
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($book);
		    case "2": return $this->transformForV2($book);
		    case "4":
		    default: return $this->transformForV4($book);
	    }
    }

    public function transformForV2($bibleBook) {

		switch(\Route::currentRouteName()) {
			case "v2_library_bookorder": {
				return [
					"book_order"  => $bibleBook->book->book_order,
					"book_id"     => $bibleBook->book->id,
					"book_name"   => $bibleBook->book->name,
					"dam_id_root" => $bibleBook->abbr
				];
			}

			case "v2_library_book": {
				return [
					"dam_id"             => $bibleBook->abbr,
					"book_id"            => $bibleBook->book->osis->code,
					"book_name"          => $bibleBook->name,
					"book_order"         => $bibleBook->book->book_order,
					"number_of_chapters" => $bibleBook->book->chapters,
					"chapters"           => $bibleBook->chapters
				];
			}

			case "v2_library_bookname": {
				return [
					$bibleBook->book->osis->code => $bibleBook->name
				];
			}

			case "v2_library_bookname": {
				return [
					$bibleBook->book->osis->code => $bibleBook->name
				];
			}

		}



    }

	public function transformForV4(Book $book) {
		return [
			"ENGKJVO",
			"Gen",
			"Genesis",
			"1",
			"50",
			"1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50"
		];
	}

}
