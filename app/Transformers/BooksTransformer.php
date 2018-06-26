<?php

namespace App\Transformers;

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
		    case "2": return $this->transformForV2($book);
		    case "3": return $this->transformForV3($book);
		    case "4":
		    default: return $this->transformForV4($book);
	    }
    }

    public function transformForV2($book) {

		switch($this->route) {
			case "v2_library_bookOrder": {
				return [
					"book_order"  => (string) $book->protestant_order,
					"book_id"     => $book->id,
					"book_name"   => $book->name,
					"dam_id_root" => $book->source_id
				];
			}

			/**
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

    public function transformForV3($book) {
	    switch ( $this->route ) {
		    case "v3_query": {
		    	$manufactured_id = strval(random_int(0,20000));
			    return [
				    "id"           => $manufactured_id,
				    "name"         => $book->name,
				    "book_code"    => $book->id,
				    "created_at"   => $book->created_at->toDateTimeString(),
				    "updated_at"   => $book->updated_at->toDateTimeString(),
				    "sort_order"   => strval($book->protestant_order),
				    "volume_id"    => "3070",
				    "enabled"      => "1",
				    "dam_id"       => $book->bible_id,
				    "chapter_list" => implode( ",", $book->sophia_chapters ),
				    "_links"       => [
					    "self" => [ "href" => "http://v3.dbt.io/search/$manufactured_id" ]
				    ]
			    ];
		    }

		    case "v3_books": {
			    $manufactured_id = strval(random_int(0,20000));
		    	return [
				    "id"           => $manufactured_id,
                    "name"         => $book->name,
                    "dam_id"       => $book->bible_id,
                    "book_code"    => $book->id,
                    "order"        => strval($book->protestant_order),
                    "enabled"      => true,
				    "chapters"     => $book->chapters,
				    "chapter_list" => $book->chapters->pluck('number')->implode(','),
				    "_links"       => [
					    "self" => [ "href" => "http://v3.dbt.io/search/$manufactured_id" ]
				    ]
			    ];
		    }

	    }
	    return [];
    }

	/**
	 * @OAS\Schema (
			*	type="array",
			*	schema="v4_bible.allBooks",
			*	description="The books of the bible with codes",
			*	title="v4_bible.allBooks",
			*	@OAS\Xml(name="v4_bible.allBooks"),
			*	@OAS\Items(
	 *          @OAS\Property(property="id",                ref="#/components/schemas/Book/properties/id"),
	 *          @OAS\Property(property="id_usfx",           ref="#/components/schemas/Book/properties/id_usfx"),
	 *          @OAS\Property(property="id_osis",           ref="#/components/schemas/Book/properties/id_osis"),
	 *          @OAS\Property(property="book_order",        ref="#/components/schemas/Book/properties/protestant_order"),
	 *          @OAS\Property(property="testament_order",   ref="#/components/schemas/Book/properties/testament_order"),
	 *          @OAS\Property(property="book_testament",    ref="#/components/schemas/Book/properties/book_testament"),
	 *          @OAS\Property(property="book_group",        ref="#/components/schemas/Book/properties/book_group"),
	 *          @OAS\Property(property="chapters",          ref="#/components/schemas/Book/properties/chapters"),
	 *          @OAS\Property(property="verses",            ref="#/components/schemas/Book/properties/verses"),
	 *          @OAS\Property(property="name",              ref="#/components/schemas/Book/properties/name"),
	 *     )
	 *   )
	 * )
	 *
	 * @OAS\Schema (
			*	type="array",
			*	schema="v4_bible.books",
			*	description="The books of the bible with codes",
			*	title="v4_bible.books",
			*	@OAS\Xml(name="v4_bible.books"),
			*	@OAS\Items(
	 *          @OAS\Property(property="id",                ref="#/components/schemas/Book/properties/id"),
	 *          @OAS\Property(property="id_usfx",           ref="#/components/schemas/Book/properties/id_usfx"),
	 *          @OAS\Property(property="id_osis",           ref="#/components/schemas/Book/properties/id_osis"),
	 *          @OAS\Property(property="book_order",        ref="#/components/schemas/Book/properties/protestant_order"),
	 *          @OAS\Property(property="testament_order",   ref="#/components/schemas/Book/properties/testament_order"),
	 *          @OAS\Property(property="book_testament",    ref="#/components/schemas/Book/properties/book_testament"),
	 *          @OAS\Property(property="book_group",        ref="#/components/schemas/Book/properties/book_group"),
	 *          @OAS\Property(property="chapters",          ref="#/components/schemas/Book/properties/chapters"),
	 *          @OAS\Property(property="verses",            ref="#/components/schemas/Book/properties/verses"),
	 *          @OAS\Property(property="name",              ref="#/components/schemas/Book/properties/name"),
	 *     )
	 *   )
	 * )
	 *
	 *
	 */
	public function transformForV4($book) {
		return [
			"id"              => $book->id,
			"id_usfx"         => $book->id_usfx,
			"id_osis"         => $book->id_osis,
			"book_order"      => $book->protestant_order,
			"testament_order" => $book->testament_order,
			"book_testament"  => $book->book_testament,
			"book_group"      => $book->book_group,
			"chapters"        => $book->chapters,
			"verses"          => $book->verses,
			"name"            => $book->name,
			"translations" => ($book->relationLoaded('translations')) ? $book->translations->mapWithKeys(function ($value) {
				return [
					$value->iso => [
						"name"              => $value->name,
						"name_long"         => $value->name_long,
						"name_short"        => $value->name_short,
						"name_abbreviation" => $value->name_abbreviation,
					]];
			}) : null,
			"bible" => ($book->relationLoaded('bible')) ? $book->bible->implode('id', ', ') : null
		];
	}

}
