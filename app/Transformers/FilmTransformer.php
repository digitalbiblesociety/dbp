<?php

namespace App\Transformers;

class FilmTransformer extends BaseTransformer
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
			"title"             => ($film->translations->first() !== null) ? $film->translations->first()->name : "",
			"book_id"           => ($film->book !== null) ? $film->book->id : "",
			"path"              => $film->filepath ?? "",
			"chapter_start"     => $film->chapter_start ?? 0,
			"verse_start"       => $film->verse_start ?? 0,
			"chapter_end"       => $film->chapter_end ?? 0,
			"verse_end"         => $film->verse_end ?? 0,
			"thumbnail_image"   => $film->thumbnail ?? "",
			"references"        => [],
			"related_videos"    => $film->related
        ];
    }
}
