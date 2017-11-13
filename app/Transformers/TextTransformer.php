<?php

namespace App\Transformers;

use App\Models\Bible\Text;
class TextTransformer extends BaseTransformer
{
    public function transform(Text $text)
    {
	    switch ($this->version) {
		    case "2": return $this->transformForV2($text);
		    case "4":
		    default: return $this->transformForV4($text);
	    }
    }

    public function transformforV2($text)
    {
	    $route = \Route::currentRouteName();
	    switch($route) {
		    case "v2_text_search": {
		    	return [
			        "dam_id"           => $text->bible_id,
                    "book_name"        => $text->book->name,
                    "book_id"          => $text->book->osis_id,
                    "chapter_id"       => $text->chapter_number,
                    "verse_id"         => $text->verse_start,
                    "verse_text"       => $text->verse_text,
                    "book_order"       => $text->book->book_order,
				];
		    }

		    case "v2_text_search_group": {
		    	return [
				    "dam_id"           => $text->bible_id,
				    "book_name"        => $text->book->name,
				    "book_id"          => $text->book->osis_id,
				    "chapter_id"       => $text->chapter_number,
				    "verse_id"         => $text->verse_start,
				    "verse_text"       => $text->verse_text,
				    "results"		   => $text->resultsCount,
				    "book_order"	   => $text->book->order
			    ];
		    }

		    default: {
			    return [
				    "book_name"        => $text->book->name,
				    "book_id"          => $text->book->osis_id,
				    "book_order"       => $text->book->order,
				    "chapter_id"       => $text->chapter_number,
				    "chapter_title"    => null,
				    "verse_id"         => $text->verse_start,
				    "verse_text"       => $text->verse_text,
				    "paragraph_number" => 1
			    ];
		    }
	    }
    }


	public function transformforV4($text)
	{
		return [
			"book"             => $text->book,
			"chapter"          => $text->chapter_number,
			"verse_start"      => $text->verse_start,
			"verse_end"        => $text->verse_end,
			"verse_text"       => $text->verse_text
		];
	}

}
