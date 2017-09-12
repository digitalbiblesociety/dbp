<?php

namespace App\Transformers;

use App\Models\Bible\Book;
use League\Fractal\TransformerAbstract;

class BooksTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = checkParam('v') ?? 4;
		$this->iso = checkParam('iso', null, 'optional') ?? "eng";
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
			case "v2_library_bookOrder": {
				return [
					"book_order"  => $bibleBook->book->book_order,
					"book_id"     => $bibleBook->book->id,
					"book_name"   => $bibleBook->book->name,
					"dam_id_root" => $bibleBook->bible_id
				];
			}

			case "v2_library_book": {
				return [
					"dam_id"             => $bibleBook->bible_id,
					"book_id"            => $bibleBook->book->code_osis,
					"book_name"          => $bibleBook->name,
					"book_order"         => $bibleBook->book->book_order,
					"number_of_chapters" => $bibleBook->book->chapters,
					"chapters"           => $bibleBook->chapters
				];
			}

			case "v2_library_bookName": {
				return [
					'book_id'           => $bibleBook->book_id,
					'book_name'         => $bibleBook->name
				];
			}

			case "v2_library_chapter": {
				return [
					"dam_id"           => $bibleBook->bible_id,
                    "book_id"          => $bibleBook->book_id,
                    "chapter_id"       => $bibleBook->chapter_number,
                    "chapter_name"     => "Chapter " . $bibleBook->chapter_number,
                    "default"          => ""
				];
			}

		}



    }

	public function transformForV4(Book $book) {
		return [
			$book
		];
	}

}
