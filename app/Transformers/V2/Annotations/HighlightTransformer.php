<?php

namespace App\Transformers\V2\Annotations;

use App\Models\User\Study\Highlight;
use League\Fractal\TransformerAbstract;

class HighlightTransformer extends TransformerAbstract
{
	/**
	 * This transformer modifies the Highlight response to reflect
	 * the expected return for the old Version 2 DBP api routes
	 * and regenerates the aged dam_id from the new bible_id
	 *
	 * @param Highlight $highlight
	 * @return array
	 */
	public function transform(Highlight $highlight)
	{
		$dam_id = $highlight->bible_id.substr($highlight->book_testament,0,1).'2ET';
		return [
			'id'                   => (string) $highlight->id,
			'user_id'              => (string) $highlight->user_id,
			'dam_id'               => $dam_id,
			'book_id'              => (string) $highlight->book_id,
			'chapter_id'           => (string) $highlight->chapter,
			'verse_id'             => (string) $highlight->verse_start,
			'color'                => $highlight->color->name ?? 'green',
			'created'              => (string) $highlight->created_at,
			'updated'              => (string) $highlight->updated_at,
			'dbt_data'             => [[
				'book_name'        => (string) $highlight->book_name,
				'book_id'          => (string) $highlight->book_id,
				'book_order'       => (string) $highlight->protestant_order,
				'chapter_id'       => (string) $highlight->chapter,
				'chapter_title'    => 'Chapter '.$highlight->chapter,
				'verse_id'         => (string) $highlight->verse_start,
				'verse_text'       => '',
				'paragraph_number' => '1'
			]]
		];
	}
}
