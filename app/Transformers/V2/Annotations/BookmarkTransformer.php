<?php

namespace App\Transformers\V2\Annotations;

use App\Models\User\Study\Bookmark;
use League\Fractal\TransformerAbstract;

class BookmarkTransformer extends TransformerAbstract
{
	/**
	 * This transformer modifies the Bookmark response to reflect
	 * the expected return for the old Version 2 DBP api route
	 * and regenerates the old dam_id from the new bible_id
	 *
	 * @param Bookmark $bookmark
	 * @return array
	 */
    public function transform(Bookmark $bookmark)
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
				'chapter_title'    => trans('api.chapter_title_prefix').' '.$bookmark->chapter,
				'verse_id'         => (string) $bookmark->verse_start,
				'verse_text'       => $bookmark->verse_text ?? '',
				'paragraph_number' => '1'
			]]
        ];
    }
}
