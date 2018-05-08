<?php

namespace App\Transformers;

use App\Models\Bible\Text;

class TextTransformer extends BaseTransformer
{
    public function transform($text)
    {
	    switch ($this->version) {
		    case "2":
		    case "3": return $this->transformForV2($text);
		    case "4":
		    default: return $this->transformForV4($text);
	    }
    }

    public function transformforV2($text)
    {
	    switch($this->route) {
		    case "v2_text_search": {
		    	return [
			        "dam_id"           => $text->bible_id,
                    "book_name"        => $text->book_name,
                    "book_id"          => $text->osis_id,
                    "chapter_id"       => "$text->chapter",
                    "verse_id"         => "$text->verse_start",
                    "verse_text"       => "$text->verse_text",
                    "book_order"       => "$text->book_order"
				];
		    }

		    case "v2_text_search_group": {
		    	return [
				    "dam_id"           => $text->bible_id,
				    "book_name"        => $text->book_name,
				    "book_id"          => $text->osis_id,
				    "chapter_id"       => "$text->chapter",
				    "verse_id"         => "$text->verse_start",
				    "verse_text"       => $text->verse_text,
				    "results"		   => "$text->resultsCount",
				    "book_order"	   => "$text->book_order"
			    ];
		    }

		    default: {
			    return [
				    "book_name"        => $text->book_name,
				    "book_id"          => $text->osis_id,
				    "book_order"       => "$text->book_order",
				    "chapter_id"       => "$text->chapter",
				    "chapter_title"    => "Chapter $text->chapter",
				    "verse_id"         => "$text->verse_start",
				    "verse_text"       => "$text->verse_text",
				    "paragraph_number" => "1"
			    ];
		    }
	    }
    }


	/**
	 * @OAS\Response(
	 *   response="v4_bible_filesets.chapter",
	 *   description="The bible chapter response",
	 *   @OAS\MediaType(
	 *     mediaType="application/json",
	 *     @OAS\Schema(
	 *              required={"name","script","family","type","direction"},
	 *              @OAS\Property(property="book_id",           ref="#/components/schemas/Book/properties/id"),
	 *              @OAS\Property(property="book_name",         ref="#/components/schemas/Book/properties/name"),
	 *              @OAS\Property(property="book_name_alt",     ref="#/components/schemas/BookTranslation/properties/name"),
	 *              @OAS\Property(property="chapter",           ref="#/components/schemas/BibleFile/properties/chapter_start"),
	 *              @OAS\Property(property="chapter_alt",       ref="#/components/schemas/BibleFile/properties/chapter_start"),
	 *              @OAS\Property(property="verse_start",       ref="#/components/schemas/BibleFile/properties/verse_start"),
	 *              @OAS\Property(property="verse_start_alt",   ref="#/components/schemas/BibleFile/properties/verse_start"),
	 *              @OAS\Property(property="verse_end",         ref="#/components/schemas/BibleFile/properties/verse_end"),
	 *              @OAS\Property(property="verse_end_alt",     ref="#/components/schemas/BibleFile/properties/verse_end"),
	 *              @OAS\Property(property="verse_text",type="string")
	 *     )
	 *   )
	 * )
	 */
	public function transformforV4($text)
	{
		return [
			"book_id"          => $text->usfm_id,
			"book_name"        => $text->book_name,
			"book_name_alt"    => $text->book_vernacular_name,
			"chapter"          => $text->chapter,
			"chapter_alt"      => (string) $text->chapter_vernacular,
			"verse_start"      => $text->verse_start,
			"verse_start_alt"  => (string) $text->verse_start_vernacular,
			"verse_end"        => $text->verse_end,
			"verse_end_alt"    => (string) $text->verse_end_vernacular,
			"verse_text"       => $text->verse_text
		];
	}

}
