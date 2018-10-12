<?php

namespace App\Transformers\V2\Annotations;

use League\Fractal\TransformerAbstract;

class HighlightTransformer extends TransformerAbstract
{
	/**
	 * A Fractal transformer.
	 *
	 * @return array
	 */
	public function transform($highlight)
	{
		$dam_id = $highlight->fileset_id.substr($highlight->book_testament,0,1).'2ET';
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