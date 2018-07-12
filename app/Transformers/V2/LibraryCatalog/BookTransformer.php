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
		     * @OAS\Schema (
		     *	type="array",
		     *	schema="v2_library_bookOrder",
		     *	description="The book return",
		     *	title="v2_library_bookOrder",
		     *	@OAS\Xml(name="v2_library_bookOrder"),
		     *	@OAS\Items(
		     *          @OAS\Property(property="dam_id_root", description="Seven character DAM ID used to define a book order", @OAS\Schema(type="string"),
		     *          @OAS\Property(property="book_id",               ref="#/components/schemas/Book/properties/id"),
		     *          @OAS\Property(property="book_name",             ref="#/components/schemas/Book/properties/name"),
		     *          @OAS\Property(property="book_order",            ref="#/components/schemas/Book/properties/protestant_order")
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
		     * @OAS\Schema (
		     *	type="array",
		     *	schema="v2_library_book",
		     *	description="The book return",
		     *	title="v2_library_book",
		     *	@OAS\Xml(name="v2_library_book"),
		     *	@OAS\Items(
		     *          @OAS\Property(property="dam_id",                ref="#/components/schemas/Bible/properties/id"),
		     *          @OAS\Property(property="book_id",               ref="#/components/schemas/Book/properties/id"),
		     *          @OAS\Property(property="book_name",             ref="#/components/schemas/Book/properties/name"),
		     *          @OAS\Property(property="book_order",            ref="#/components/schemas/Book/properties/protestant_order"),
		     *          @OAS\Property(property="number_of_chapters",    ref="#/components/schemas/Book/properties/chapters"),
		     *          @OAS\Property(property="chapters",              ref="#/components/schemas/Book/properties/chapters"),
		     *     )
		     *   )
		     * )
		     *
		     */
		    case "v2_library_book": {
			    return [
				    "dam_id"             => $book->source_id,
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
		     * @OAS\Schema (
		     *	type="array",
		     *	schema="v2_library_chapter",
		     *	description="The book return",
		     *	title="v2_library_chapter",
		     *	@OAS\Xml(name="v2_library_chapter"),
		     *	@OAS\Items(
		     *          @OAS\Property(property="dam_id",                ref="#/components/schemas/Bible/properties/id"),
		     *          @OAS\Property(property="book_id",               ref="#/components/schemas/Book/properties/id"),
		     *          @OAS\Property(property="chapter_id",            ref="#/components/schemas/Book/properties/chapter_id"),
		     *          @OAS\Property(property="chapter_name",          ref="#/components/schemas/Book/properties/chapter_name"),
		     *          @OAS\Property(property="default",               @OAS\Schema(type="string")),
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
