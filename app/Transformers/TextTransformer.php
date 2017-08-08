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
    	return [
		    "book_name"        => $text->book->name,
            "book_id"          => $text->book->osis->code,
            "book_order"       => $text->book->order,
            "chapter_id"       => $text->chapter_number,
            "chapter_title"    => null,
            "verse_id"         => $text->verse_start,
            "verse_text"       => $text->verse_text,
            "paragraph_number" => 1
	    ];
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
