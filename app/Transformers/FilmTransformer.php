<?php

namespace App\Transformers;

class FilmTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @OA\Schema (
     *    type="array",
     *    schema="v2_video_path",
     *    description="The v2_video_path",
     *    title="v2_video_path",
     *  @OA\Xml(name="v2_video_path"),
     *  @OA\Items(
     *        @OA\Property(property="segment_order",      ref="#/components/schemas/Video/properties/id"),
     *        @OA\Property(property="title",              ref="#/components/schemas/VideoTag/properties/tag"),
     *        @OA\Property(property="book_id",            ref="#/components/schemas/Book/properties/id"),
     *        @OA\Property(property="path",               ref="#/components/schemas/Video/properties/url"),
     *        @OA\Property(property="chapter_start",      ref="#/components/schemas/VideoTag/properties/chapter_start"),
     *        @OA\Property(property="verse_start",        ref="#/components/schemas/VideoTag/properties/verse_start"),
     *        @OA\Property(property="chapter_end",        ref="#/components/schemas/VideoTag/properties/chapter_end"),
     *        @OA\Property(property="verse_end",          ref="#/components/schemas/VideoTag/properties/verse_end"),
     *        @OA\Property(property="thumbnail_image",    ref="#/components/schemas/Video/properties/picture"),
     *        @OA\Property(property="references",         @OA\Schema(type="object")),
     *        @OA\Property(property="related_videos",     ref="#/components/schemas/Video")
     *     )
     *   )
     * )
     *
     * @param $film
     *
     * @return array
     */
    public function transform($film)
    {
        return [
            'segment_order' => 1,
            'title'         => ($film->translations->first() !== null) ? $film->translations->first()->name : '',
            'book_id'       => ($film->book !== null) ? $film->book->id : '',
            'path'          => $film->filepath ?? '',
            'chapter_start' => (string) $film->chapter_start,
            'verse_start'   => (string) $film->verse_start,
            'chapter_end'   => (string) $film->chapter_end,
            'verse_end'     => (string) $film->verse_end,
            'thumbnail_image' => $film->thumbnail ?? '',
            'references'      => [],
            'related_videos'  => $film->related
        ];
    }
}
