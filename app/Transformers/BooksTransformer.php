<?php

namespace App\Transformers;

use App\Models\Bible\Book;
use Faker\Provider\Base;

class BooksTransformer extends BaseTransformer
{
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

    public function transformForV2($book) {

		switch($this->route) {
			case "v2_library_bookOrder": {
				return [
					"book_order"  => $book->book_order,
					"book_id"     => $book->id,
					"book_name"   => $book->name,
					"dam_id_root" => $book->bible_id
				];
			}

			case "v2_library_book": {
				return [
					"dam_id"             => $book->bible_id.substr($book->book_testament,0,1),
					"book_id"            => $book->id_osis,
					"book_name"          => $book->name,
					"book_order"         => $book->book_order,
					"number_of_chapters" => count($book->sophia_chapters),
					"chapters"           => implode(",",$book->sophia_chapters)
				];
			}

			case "v2_library_chapter": {
				return [
					"dam_id"           => $book->bible_id,
                    "book_id"          => $book->book->id_osis,
                    "chapter_id"       => $book->chapter,
                    "chapter_name"     => "Chapter " . $book->chapter,
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
