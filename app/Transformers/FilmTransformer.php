<?php

namespace App\Transformers;

class FilmTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @OAS\Response(
     *   response="v2_video_path",
     *   description="The v2_video_path",
     *   @OAS\MediaType(
     *     mediaType="application/json",
     *     @OAS\Schema(
     *        @OAS\Property(property="segment_order",      ref="#/components/schemas/Video/properties/id"),
     *        @OAS\Property(property="title",              ref="#/components/schemas/VideoTag/properties/tag"),
     *        @OAS\Property(property="book_id",            ref="#/components/schemas/Book/properties/id"),
     *        @OAS\Property(property="path",               ref="#/components/schemas/Video/properties/url"),
     *        @OAS\Property(property="chapter_start",      ref="#/components/schemas/VideoTag/properties/chapter_start"),
     *        @OAS\Property(property="verse_start",        ref="#/components/schemas/VideoTag/properties/verse_start"),
     *        @OAS\Property(property="chapter_end",        ref="#/components/schemas/VideoTag/properties/chapter_end"),
     *        @OAS\Property(property="verse_end",          ref="#/components/schemas/VideoTag/properties/verse_end"),
     *        @OAS\Property(property="thumbnail_image",    ref="#/components/schemas/Video/properties/picture"),
     *        @OAS\Property(property="references",         @OAS\Schema(type="array")),
     *        @OAS\Property(property="related_videos",     ref="#/components/schemas/Video")
     *     )
     *   )
     * )
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
			"chapter_start"     => (string) $film->chapter_start ?? "0",
			"verse_start"       => (string) $film->verse_start ?? "0",
			"chapter_end"       => (string) $film->chapter_end ?? "0",
			"verse_end"         => (string) $film->verse_end ?? "0",
			"thumbnail_image"   => $film->thumbnail ?? "",
			"references"        => [],
			"related_videos"    => $film->related
        ];
    }
}
