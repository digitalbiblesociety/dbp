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
		    case "3": return $this->transformForV3($book);
		    case "4":
		    default: return $this->transformForV4($book);
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
	 * @OA\Schema (
			*	type="array",
			*	schema="v4_bible.allBooks",
			*	description="The books of the bible with codes",
			*	title="v4_bible.allBooks",
			*	@OA\Xml(name="v4_bible.allBooks"),
			*	@OA\Items(
	 *          @OA\Property(property="id",                ref="#/components/schemas/Book/properties/id"),
	 *          @OA\Property(property="id_usfx",           ref="#/components/schemas/Book/properties/id_usfx"),
	 *          @OA\Property(property="id_osis",           ref="#/components/schemas/Book/properties/id_osis"),
	 *          @OA\Property(property="book_order",        ref="#/components/schemas/Book/properties/protestant_order"),
	 *          @OA\Property(property="testament_order",   ref="#/components/schemas/Book/properties/testament_order"),
	 *          @OA\Property(property="book_testament",    ref="#/components/schemas/Book/properties/book_testament"),
	 *          @OA\Property(property="book_group",        ref="#/components/schemas/Book/properties/book_group"),
	 *          @OA\Property(property="chapters",          ref="#/components/schemas/Book/properties/chapters"),
	 *          @OA\Property(property="verses",            ref="#/components/schemas/Book/properties/verses"),
	 *          @OA\Property(property="name",              ref="#/components/schemas/Book/properties/name"),
	 *     )
	 *   )
	 * )
	 *
	 * @OA\Schema (
			*	type="array",
			*	schema="v4_bible.books",
			*	description="The books of the bible with codes",
			*	title="v4_bible.books",
			*	@OA\Xml(name="v4_bible.books"),
			*	@OA\Items(
	 *          @OA\Property(property="id",                ref="#/components/schemas/Book/properties/id"),
	 *          @OA\Property(property="id_usfx",           ref="#/components/schemas/Book/properties/id_usfx"),
	 *          @OA\Property(property="id_osis",           ref="#/components/schemas/Book/properties/id_osis"),
	 *          @OA\Property(property="book_order",        ref="#/components/schemas/Book/properties/protestant_order"),
	 *          @OA\Property(property="testament_order",   ref="#/components/schemas/Book/properties/testament_order"),
	 *          @OA\Property(property="book_testament",    ref="#/components/schemas/Book/properties/book_testament"),
	 *          @OA\Property(property="book_group",        ref="#/components/schemas/Book/properties/book_group"),
	 *          @OA\Property(property="chapters",          ref="#/components/schemas/Book/properties/chapters"),
	 *          @OA\Property(property="verses",            ref="#/components/schemas/Book/properties/verses"),
	 *          @OA\Property(property="name",              ref="#/components/schemas/Book/properties/name"),
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
