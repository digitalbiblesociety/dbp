<?php

namespace App\Transformers;

class TextTransformer extends BaseTransformer
{
    public function transform($text)
    {
        switch ($this->version) {
            case 2:
            case 3:
                return $this->transformForV2($text);
                break;
            case 4:
            default:
                return $this->transformForV4($text);
        }
    }

    public function transformForV2($text)
    {
        switch ($this->route) {

            /**
            * @OA\Schema (
            *   type="array",
            *   schema="v2_text_search",
            *   description="The v2_text_search",
            *   title="v2_text_search",
            *   @OA\Xml(name="v2_text_search"),
            *   @OA\Items(
            *     @OA\Property(property="dam_id",     ref="#/components/schemas/Bible/properties/id"),
            *     @OA\Property(property="book_name",  ref="#/components/schemas/Book/properties/name"),
            *     @OA\Property(property="book_id",    ref="#/components/schemas/Book/properties/id_osis"),
            *     @OA\Property(property="chapter_id", ref="#/components/schemas/BibleFile/properties/chapter_start"),
            *     @OA\Property(property="verse_id",   ref="#/components/schemas/BibleFile/properties/verse_start"),
            *     @OA\Property(property="verse_text", ref="#/components/schemas/BibleFile/properties/verse_text"),
            *     @OA\Property(property="book_order", ref="#/components/schemas/Book/properties/protestant_order")
            *     )
            *   )
            * )
            */
            case 'v2_text_search':
                return [
                    'dam_id'           => (string) $_GET['dam_id'],
                    'book_name'        => (string) $text->book_name,
                    'book_id'          => (string) $text->book_id,
                    'chapter_id'       => (string) $text->chapter,
                    'verse_id'         => (string) $text->verse_start,
                    'verse_text'       => (string) $text->verse_text,
                    'book_order'       => (string) $text->protestant_order
                ];

            /**
             * @OA\Schema (
             *   type="array",
             *   schema="v2_text_search_group",
             *   description="The bible Search Group Response",
             *   title="v2_text_search_group",
             *   @OA\Xml(name="v2_text_search_group"),
             *   @OA\Items(
             *              @OA\Property(property="dam_id",     ref="#/components/schemas/Bible/properties/id"),
             *              @OA\Property(property="book_name",  ref="#/components/schemas/Book/properties/name"),
             *              @OA\Property(property="book_id",    ref="#/components/schemas/Book/properties/id_osis"),
             *              @OA\Property(property="chapter_id", ref="#/components/schemas/BibleFile/properties/chapter_start"),
             *              @OA\Property(property="verse_id",   ref="#/components/schemas/BibleFile/properties/verse_start"),
             *              @OA\Property(property="verse_text", ref="#/components/schemas/BibleFile/properties/verse_text"),
             *              @OA\Property(property="results",    @OA\Schema(type="integer",minimum=0,example=45)),
             *              @OA\Property(property="book_order", ref="#/components/schemas/Book/properties/protestant_order")
             *     )
             *   )
             * )
             */
            case 'v2_text_search_group':
                return [
                    'dam_id'           => $text->bible_id,
                    'book_name'        => $text->book_name,
                    'book_id'          => $text->id_osis,
                    'chapter_id'       => (string) $text->chapter,
                    'verse_id'         => (string) $text->verse_start,
                    'verse_text'       => $text->verse_text,
                    'results'          => (string) $text->resultsCount,
                    'book_order'       => (string) $text->protestant_order
                ];

            /**
             * @OA\Schema (
             *   type="array",
             *   schema="v2_text_verse",
             *   description="The bible Search Group Response",
             *   title="v2_text_verse",
             *   @OA\Xml(name="v2_text_verse"),
             *   @OA\Items(
             *              @OA\Property(property="book_name",         ref="#/components/schemas/Book/properties/name"),
             *              @OA\Property(property="book_id",           ref="#/components/schemas/Book/properties/id_osis"),
             *              @OA\Property(property="chapter_id",        ref="#/components/schemas/BibleFile/properties/chapter_start"),
             *              @OA\Property(property="chapter_title",     @OA\Schema(type="string",example="Chapter 1")),
             *              @OA\Property(property="verse_id",          ref="#/components/schemas/BibleFile/properties/verse_start"),
             *              @OA\Property(property="verse_text",        ref="#/components/schemas/BibleFile/properties/verse_text"),
             *              @OA\Property(property="paragraph_number",  @OA\Schema(type="string",example="2"))
             *     )
             *   )
             * )
             */
            default:
                return [
                    'book_name'        => (string) $text->book_name,
                    'book_id'          => (string) $text->osis_id,
                    'book_order'       => (string) $text->protestant_order,
                    'chapter_id'       => (string) $text->chapter,
                    'chapter_title'    => "Chapter $text->chapter",
                    'verse_id'         => (string) $text->verse_start,
                    'verse_text'       => (string) $text->verse_text,
                    'paragraph_number' => (string) 1
                ];
        }
    }


    /**
     * @OA\Schema (
     *    type="array",
     *    schema="v4_bible_filesets_chapter",
     *    description="The bible chapter response",
     *    title="v4_bible_filesets_chapter",
     *  @OA\Xml(name="v4_bible_filesets_chapter"),
     *  @OA\Items(              required={"name","script","family","type","direction"},
     *              @OA\Property(property="book_id",           ref="#/components/schemas/Book/properties/id"),
     *              @OA\Property(property="book_name",         ref="#/components/schemas/Book/properties/name"),
     *              @OA\Property(property="book_name_alt",     ref="#/components/schemas/BookTranslation/properties/name"),
     *              @OA\Property(property="chapter",           ref="#/components/schemas/BibleFile/properties/chapter_start"),
     *              @OA\Property(property="chapter_alt",       ref="#/components/schemas/BibleFile/properties/chapter_start"),
     *              @OA\Property(property="verse_start",       ref="#/components/schemas/BibleFile/properties/verse_start"),
     *              @OA\Property(property="verse_start_alt",   ref="#/components/schemas/BibleFile/properties/verse_start"),
     *              @OA\Property(property="verse_end",         ref="#/components/schemas/BibleFile/properties/verse_end"),
     *              @OA\Property(property="verse_end_alt",     ref="#/components/schemas/BibleFile/properties/verse_end"),
     *              @OA\Property(property="verse_text",type="string")
     *     )
     *   )
     * )
     *
     * @param $text
     * @return array
     */
    public function transformForV4($text)
    {
        return [
            'book_id'          => $text->book_id,
            'book_name'        => $text->book_name,
            'book_name_alt'    => $text->book_vernacular_name,
            'chapter'          => $text->chapter,
            'chapter_alt'      => (string) $text->chapter_vernacular,
            'verse_start'      => $text->verse_start,
            'verse_start_alt'  => (string) $text->verse_start_vernacular,
            'verse_end'        => $text->verse_end,
            'verse_end_alt'    => (string) $text->verse_end_vernacular,
            'verse_text'       => $text->verse_text
        ];
    }
}
