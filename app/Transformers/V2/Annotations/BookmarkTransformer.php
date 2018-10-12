<?php

namespace App\Transformers\V2\Annotations;

use League\Fractal\TransformerAbstract;

class BookmarkTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($bookmark)
    {
	    $dam_id = $bookmark->bible_id.substr($bookmark->book_testament,0,1).'2ET';
        return [
			'id'                   => (string) $bookmark->id,
			'user_id'              => (string) $bookmark->user_id,
			'dam_id'               => $dam_id,
			'book_id'              => (string) $bookmark->book_id,
			'chapter_id'           => (string) $bookmark->chapter,
			'verse_id'             => (string) $bookmark->verse_start,
			'created'              => (string) $bookmark->created_at,
			'updated'              => (string) $bookmark->updated_at,
			'dbt_data'             => [[
				'book_name'        => (string) $bookmark->book_name,
				'book_id'          => (string) $bookmark->book_id,
				'book_order'       => (string) $bookmark->protestant_order,
				'chapter_id'       => (string) $bookmark->chapter,
				'chapter_title'    => 'Chapter '.$bookmark->chapter,
				'verse_id'         => (string) $bookmark->verse_start,
				'verse_text'       => $bookmark->verse_text ?? '',
				'paragraph_number' => '1'
			]]
        ];
    }
}
