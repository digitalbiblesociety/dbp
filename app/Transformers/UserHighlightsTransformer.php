<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class UserHighlightsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($highlight)
    {
        return [
	        "id"                => $highlight->id,
            "bible_id"          => $highlight->bible_id,
            "book_id"           => $highlight->book_id,
            "chapter"           => $highlight->chapter,
            "verse_start"       => $highlight->verse_start,
            "highlight_start"   => $highlight->highlight_start,
            "highlighted_words" => $highlight->highlighted_words,
	        "highlighted_color" => $highlight->highlighted_color
        ];
    }
}
