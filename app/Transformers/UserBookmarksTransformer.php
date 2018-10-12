<?php

namespace App\Transformers;

use App\Models\User\Study\Bookmark;
use League\Fractal\TransformerAbstract;

class UserBookmarksTransformer extends TransformerAbstract
{
	/**
	 * @OA\Schema (
	 *        type="array",
	 *        schema="v4_user_bookmarks",
	 *        description="The transformed user bookmarks",
	 *        title="v4_user_bookmarks",
	 *		@OA\Xml(name="v4_user_bookmarks"),
	 *		@OA\Items(
	 *			@OA\Property(property="id",             type="integer"),
	 *			@OA\Property(property="bible_id",       ref="#/components/schemas/Bible/properties/id"),
	 *			@OA\Property(property="book_id",        ref="#/components/schemas/Book/properties/id"),
	 *			@OA\Property(property="chapter",        ref="#/components/schemas/BibleFile/properties/chapter_start"),
	 *			@OA\Property(property="verse_start",    ref="#/components/schemas/BibleFile/properties/verse_start"),
	 *			@OA\Property(property="created_at",     ref="#/components/schemas/Bookmark/properties/created_at"),
	 *			@OA\Property(property="updated_at",     ref="#/components/schemas/Bookmark/properties/updated_at")
	 *        )
	 *    )
	 *)
	 *
	 * @param Bookmark $bookmark
	 * @return array
	 */
	public function transform(Bookmark $bookmark)
	{
		return [
			'id'			=> (int) $bookmark->id,
			'bible_id'		=> (string) $bookmark->bible_id,
			'book_id'		=> (string) $bookmark->book_id,
			'chapter'		=> (int) $bookmark->chapter,
			'verse'         => (int) $bookmark->verse_start,
			'created_at'	=> (string) $bookmark->created_at->toDateTimeString(),
			'updated_at'	=> (string) $bookmark->updated_at->toDateTimeString()
		];
	}
}
