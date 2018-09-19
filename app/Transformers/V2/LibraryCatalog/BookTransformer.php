<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class BookTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($book)
    {

	    switch($this->route) {

		    /**
		     *
		     * @see https://dbt.io/library/bookorder?key=111a125057abd2f8931f6d6ad9f2921f&dam_id=ENGESVN1ET&v=2
		     * @see https://api.dbp.localhost/library/bookorder?key=1234&v=2&dam_id=ENGESV
		     *
		     * @OA\Schema (
		     *	type="array",
		     *	schema="v2_library_bookOrder",
		     *	description="The book return",
		     *	title="v2_library_bookOrder",
		     *	@OA\Xml(name="v2_library_bookOrder"),
		     *	@OA\Items(
		     *          @OA\Property(property="dam_id_root", description="Seven character DAM ID used to define a book order", @OA\Schema(type="string"),
		     *          @OA\Property(property="book_id",               ref="#/components/schemas/Book/properties/id"),
		     *          @OA\Property(property="book_name",             ref="#/components/schemas/Book/properties/name"),
		     *          @OA\Property(property="book_order",            ref="#/components/schemas/Book/properties/protestant_order")
		     *     )
		     *   )
		     * )
		     *
		     */
		    case "v2_library_bookOrder": {
			    return [
				    "book_order"  => (string) $book->protestant_order,
				    "book_id"     => $book->id,
				    "book_name"   => $book->name,
				    "dam_id_root" => $book->source_id
			    ];
		    }

		    /**
		     *
		     * @see https://dbt.io/library/book?key=111a125057abd2f8931f6d6ad9f2921f&v=2&dam_id=ENGESVN1ET
		     * @see https://api.dbp.localhost/library/book?key=1234&pretty&v=2&dam_id=ENGESVN1ET
		     *
		     * @OA\Schema (
		     *	type="array",
		     *	schema="v2_library_book",
		     *	description="The book return",
		     *	title="v2_library_book",
		     *	@OA\Xml(name="v2_library_book"),
		     *	@OA\Items(
		     *          @OA\Property(property="dam_id",                ref="#/components/schemas/Bible/properties/id"),
		     *          @OA\Property(property="book_id",               ref="#/components/schemas/Book/properties/id"),
		     *          @OA\Property(property="book_name",             ref="#/components/schemas/Book/properties/name"),
		     *          @OA\Property(property="book_order",            ref="#/components/schemas/Book/properties/protestant_order"),
		     *          @OA\Property(property="number_of_chapters",    ref="#/components/schemas/Book/properties/chapters"),
		     *          @OA\Property(property="chapters",              ref="#/components/schemas/Book/properties/chapters"),
		     *     )
		     *   )
		     * )
		     *
		     */
		    case "v2_library_book": {
			    return [
			    	"dam_id_root"        => $book->source_id,
				    "book_id"            => $book->id_osis,
				    "book_name"          => $book->name,
				    "book_order"         => (string) $book->protestant_order,
				    "number_of_chapters" => (string) $book->number_chapters,
				    "chapters"           => (string) $book->chapters
			    ];
		    }


		    /**
		     *
		     * @see https://dbt.io/library/book?key=111a125057abd2f8931f6d6ad9f2921f&v=2&dam_id=ENGESVN1ET
		     * @see https://api.dbp.localhost/library/book?key=1234&pretty&v=2&dam_id=ENGESVN1ET
		     *
		     * @OA\Schema (
		     *	type="array",
		     *	schema="v2_library_chapter",
		     *	description="The book return",
		     *	title="v2_library_chapter",
		     *	@OA\Xml(name="v2_library_chapter"),
		     *	@OA\Items(
		     *          @OA\Property(property="dam_id",                ref="#/components/schemas/Bible/properties/id"),
		     *          @OA\Property(property="book_id",               ref="#/components/schemas/Book/properties/id"),
		     *          @OA\Property(property="chapter_id",            ref="#/components/schemas/BibleFile/properties/chapter_start"),
		     *          @OA\Property(property="chapter_name",          ref="#/components/schemas/BibleFile/properties/chapter_start"),
		     *          @OA\Property(property="default",               @OA\Schema(type="string")),
		     *     )
		     *   )
		     * )
		     *
		     */
		    case "v2_library_chapter": {
			    return [
				    "dam_id"           => $book->source_id,
				    "book_id"          => $book->book_id,
				    "chapter_id"       => (string) $book->chapter,
				    "chapter_name"     => "Chapter " . $book->chapter,
				    "default"          => ""
			    ];
		    }

	    }
    }
}
