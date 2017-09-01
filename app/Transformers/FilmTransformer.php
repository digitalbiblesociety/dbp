<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class FilmTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($film)
    {
        return [
			"segment_order"     => 1,
			"title"             => $film->title,
			"book_id"           => $film->book->id,
			"path"              => $film->filepath,
			"chapter_start"     => $film->chapter_start,
			"verse_start"       => $film->verse_start,
			"chapter_end"       => $film->chapter_end,
			"verse_end"         => $film->verse_end,
			"thumbnail_image"   => $film->thumbnail,
			"references"        => [],
			"related_videos"    => [
					"video_type" => "Topic",
					"path"       => $film->related->where('type','topic')->first()->filepath
				],
				[
					"video_type" => "Intro",
					"path"       => $film->related->where('type','intro')->first()->filepath
				],
	            [
					"video_type" => "More Info",
					"path"       => $film->related->where('type','info')->first()->filepath
				]
        ];
    }
}
