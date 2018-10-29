<?php

namespace App\Transformers;

use App\Models\User\Study\Highlight;
use League\Fractal\TransformerAbstract;

class UserHighlightsTransformer extends TransformerAbstract
{
	/**
	 * @OA\Schema (
			*	type="array",
			*	schema="v4_highlights_index",
			*    description="The v4 highlights index response. Note the fileset_id is being used to identify the item instead of the bible_id.
	                   This is important as different filesets may have different numbers for the highlighted words field depending on their revision.",
			*	title="v4_highlights_index",
			*	@OA\Xml(name="v4_highlights_index"),
			*	@OA\Items(
	 *              @OA\Property(property="id",                     ref="#/components/schemas/Highlight/properties/id"),
	 *              @OA\Property(property="fileset_id",             ref="#/components/schemas/BibleFileset/properties/id"),
	 *              @OA\Property(property="book_id",                ref="#/components/schemas/Book/properties/id"),
	 *              @OA\Property(property="book_name",              ref="#/components/schemas/BibleBook/properties/name"),
	 *              @OA\Property(property="chapter",                ref="#/components/schemas/BibleFile/properties/chapter_start"),
	 *              @OA\Property(property="verse",                  ref="#/components/schemas/BibleFile/properties/verse_start"),
	 *              @OA\Property(property="highlight_start",        ref="#/components/schemas/Highlight/properties/highlight_start"),
	 *              @OA\Property(property="highlighted_words",      ref="#/components/schemas/Highlight/properties/highlighted_words"),
	 *              @OA\Property(property="highlighted_color",      ref="#/components/schemas/Highlight/properties/highlighted_color")
	 *           ),
	 *     )
	 *   )
	 * )
	 */
    public function transform(Highlight $highlight)
    {
		$this->checkColorPreference($highlight);

        return [
	        'id'                => (int) $highlight->id,
            'bible_id'        => (string) $highlight->bible_id,
            'book_id'           => (string) $highlight->book_id,
	        'book_name'         => (string) $highlight->book->name,
            'chapter'           => (int) $highlight->chapter,
            'verse_start'       => (int) $highlight->verse_start,
            'highlight_start'   => (int) $highlight->highlight_start,
            'highlighted_words' => (int) $highlight->highlighted_words,
	        'highlighted_color' => $highlight->color
        ];
    }

    private function checkColorPreference($highlight) {
	    $color_preference = checkParam('prefer_color', null,'optional') ?? 'rgba';
	    if($color_preference == 'hex')  $highlight->color = '#'.$highlight->color->hex;
	    if($color_preference == 'rgb')  $highlight->color = 'rgb('.$highlight->color->red.','.$highlight->color->green.','.$highlight->color->blue.')';
	    if($color_preference == 'rgba') $highlight->color = 'rgba('.$highlight->color->red.','.$highlight->color->green.','.$highlight->color->blue.','.$highlight->color->opacity.')';
	}


}
