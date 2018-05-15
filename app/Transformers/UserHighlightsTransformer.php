<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class UserHighlightsTransformer extends TransformerAbstract
{
	/**
	 * @OAS\Response(
	 *   response="v4_highlights_index",
	 *   description="The v4 highlights index response",
	 *   @OAS\MediaType(
	 *     mediaType="application/json",
	 *     @OAS\Schema(
	 *              @OAS\Property(property="book_id",       ref="#/components/schemas/Book/properties/id"),
	 *              @OAS\Property(property="book_name",     ref="#/components/schemas/Book/properties/name"),
	 *              @OAS\Property(property="chapter_start", ref="#/components/schemas/BibleFile/properties/chapter_start"),
	 *              @OAS\Property(property="chapter_end",   ref="#/components/schemas/BibleFile/properties/chapter_end"),
	 *              @OAS\Property(property="verse_start",   ref="#/components/schemas/BibleFile/properties/verse_start"),
	 *              @OAS\Property(property="reference",   ref="#/components/schemas/BibleFile/properties/reference"),
	 *              @OAS\Property(property="verse_end",     ref="#/components/schemas/BibleFile/properties/verse_end"),
	 *              @OAS\Property(property="timestamp",     ref="#/components/schemas/BibleFileTimestamp/properties/timestamp"),
	 *              @OAS\Property(property="path",          ref="#/components/schemas/BibleFile/properties/file_name")
	 *     )
	 *   )
	 * )
	 */
    public function transform($highlight)
    {
        return [
	        "id"                => $highlight->id,
            "bible_id"          => $highlight->bible_id,
            "book_id"           => $highlight->book_id,
            "chapter"           => $highlight->chapter,
            "verse_start"       => $highlight->verse_start,
	        "reference"         => $highlight->reference,
            "highlight_start"   => intval($highlight->highlight_start),
            "highlighted_words" => intval($highlight->highlighted_words),
	        "highlighted_color" => $highlight->highlighted_color
        ];
    }
}
