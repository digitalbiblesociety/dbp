<?php

namespace App\Transformers;

class AudioTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($audio)
    {
        switch ($this->version) {
            case 2:
            case 3:
                return $this->transformForV2($audio);
            case 4:
            default:
                return $this->transformForV4($audio);
        }
    }

    public function transformForV2($audio)
    {
        switch ($this->route) {

                /**
             * @OA\Schema (
             *   type="array",
             *   schema="v2_audio_timestamps",
             *   description="The v2_audio_timestamps response",
             *   title="v2_audio_timestamps",
             *   @OA\Xml(name="v2_audio_timestamps"),
             *   @OA\Items(
             *               @OA\Property(property="verse_start",             ref="#/components/schemas/BibleFile/properties/verse_start"),
             *              @OA\Property(property="timestamp",          @OA\Schema(type="string",example="1",description="The duration of the timestamp in seconds"))
             *     )
             *   )
             * )
             */
            case 'v2_audio_timestamps':
                return [
                    'verse_start'    => (string) $audio->verse_start,
                    'timestamp'      => $audio->timestamp
                ];

                /**
                 * @OA\Schema (
                 *   type="array",
                 *   schema="v2_audio_path",
                 *   description="The audio_path",
                 *   title="v2_audio_path",
                 *   @OA\Xml(name="v2_audio_path"),
                 *   @OA\Items(
                 *              @OA\Property(property="book_id",       ref="#/components/schemas/Book/properties/id_osis"),
                 *              @OA\Property(property="chapter_id",    ref="#/components/schemas/BibleFile/properties/chapter_start"),
                 *              @OA\Property(property="path",          @OA\Schema(type="string"))
                 *     )
                 *   )
                 * )
                 */
            case 'v2_audio_path':
                return [
                    'book_id'    => $audio->book ? ucfirst(strtolower($audio->book->id_osis)) : $audio->book_id,
                    'chapter_id' => (string) $audio->chapter_start,
                    'path'       => preg_replace("/https:\/\/.*?\/.*?\//", '', $audio->file_name)
                ];
        }
    }

    public function transformForV4($audio)
    {
        switch ($this->route) {
                /**
             * @OA\Schema (
             *   type="array",
             *   schema="v4_audio_timestamps",
             *   description="The v4_audio_timestamps response",
             *   title="v4_audio_timestamps",
             *   @OA\Xml(name="v4_audio_timestamps"),
             *   @OA\Items(
             *              @OA\Property(property="book",             ref="#/components/schemas/BibleFile/properties/book_id"),
             *              @OA\Property(property="chapter",             ref="#/components/schemas/BibleFile/properties/chapter_start"),
             *              @OA\Property(property="verse_start",             ref="#/components/schemas/BibleFile/properties/verse_start"),
             *              @OA\Property(property="timestamp",          @OA\Schema(type="string",example="1",description="The duration of the timestamp in seconds"))
             *     )
             *   )
             * )
             */
            case 'v4_timestamps.verse':
                return [
                    'book'           => (string) $audio->bibleFile->book_id,
                    'chapter'        => (string) $audio->bibleFile->chapter_start,
                    'verse_start'    => (string) $audio->verse_start,
                    'timestamp'      => $audio->timestamp
                ];
            default:
                /**
                 * @OA\Schema (
                 *   type="array",
                 *   schema="v4_timestamps_tag",
                 *   description="The v4 timestamps tag",
                 *   title="v4_timestamps_tag",
                 *   @OA\Xml(name="v4_timestamps_tag"),
                 *   @OA\Items(
                 *       @OA\Property(property="book_id",       ref="#/components/schemas/Book/properties/id"),
                 *       @OA\Property(property="book_name",     ref="#/components/schemas/Book/properties/name"),
                 *       @OA\Property(property="chapter_start", ref="#/components/schemas/BibleFile/properties/chapter_start"),
                 *       @OA\Property(property="chapter_end",   ref="#/components/schemas/BibleFile/properties/chapter_end"),
                 *       @OA\Property(property="verse_start",   ref="#/components/schemas/BibleFile/properties/verse_start"),
                 *       @OA\Property(property="verse_end",     ref="#/components/schemas/BibleFile/properties/verse_end"),
                 *       @OA\Property(property="timestamp",     ref="#/components/schemas/BibleFileTimestamp/properties/timestamp"),
                 *       @OA\Property(property="path",          ref="#/components/schemas/BibleFile/properties/file_name")
                 *     )
                 *   )
                 * )
                 */
                return [
                    'book_id'       => $audio->book_id,
                    'book_name'     => $audio->book->currentTranslation->name ?? $audio->book->name,
                    'chapter_start' => $audio->chapter_start,
                    'chapter_end'   => $audio->chapter_end,
                    'verse_start'   => $audio->verse_start,
                    'verse_end'     => $audio->verse_end,
                    'timestamp'     => $audio->timestamps,
                    'path'          => $audio->file_name
                ];
        }
    }
}
