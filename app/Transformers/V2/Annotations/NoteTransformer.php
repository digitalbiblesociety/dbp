<?php

namespace App\Transformers\V2\Annotations;

use App\Models\User\Study\Note;
use League\Fractal\TransformerAbstract;

class NoteTransformer extends TransformerAbstract
{
	/**
	 * This transformer modifies the UserNote response to reflect
	 * the expected return for the old Version 2 DBP api route
	 * and regenerates the old dam_id from the new bible_id
	 *
	 * @param $note
	 * @return array
	 */
	public function transform(Note $note)
	{
		$dam_id = $note->bible_id.substr($note->book->book_testament,0,1).'2ET';
		return [
			'id'                   => (string) $note->id,
			'user_id'              => (string) $note->user_id,
			'dam_id'               => $dam_id,
			'book_id'              => (string) $note->book->id_osis,
			'chapter_id'           => (string) $note->chapter,
			'verse_id'             => (string) $note->verse_start,
			'note'                 => (string) $note->notes,
			'created'              => (string) $note->created_at,
			'updated'              => (string) $note->updated_at,
			'dbt_data'             => [[
				'book_name'        => (string) $note->book->name,
				'book_id'          => (string) $note->book->id_osis,
				'book_order'       => (string) $note->protestant_order,
				'chapter_id'       => (string) $note->chapter,
				'chapter_title'    => 'Chapter '.$note->chapter,
				'verse_id'         => (string) $note->verse_start,
				'verse_text'       => '',
				'paragraph_number' => '1'
			]]
		];
	}
}
