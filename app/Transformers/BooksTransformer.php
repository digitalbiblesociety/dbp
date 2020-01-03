<?php

namespace App\Transformers;

class BooksTransformer extends BaseTransformer
{

    /**
     * A Fractal transformer.
     *
     * @param $book
     *
     * @return array
     */
    public function transform($book)
    {
        switch ((int) $this->version) {
            case 3:
                return $this->transformForV3($book);
            case 4:
            default:
                return $this->transformForV4($book);
        }
    }

    /**
     * @param $book
     *
     * @return array
     */
    public function transformForV3($book)
    {
        switch ($this->route) {
            case 'v3_query':
                $manufactured_id = (string) random_int(0, 20000);
                return [
                    'id'           => $manufactured_id,
                    'name'         => (string) $book->name,
                    'book_code'    => (string) $book->id,
                    'created_at'   => (string) $book->created_at->toDateTimeString(),
                    'updated_at'   => (string) $book->updated_at->toDateTimeString(),
                    'sort_order'   => (string) $book->protestant_order,
                    'volume_id'    => '',
                    'enabled'      => '1',
                    'dam_id'       => $book->bible_id,
                    'chapter_list' => implode(',', $book->sophia_chapters),
                    '_links'       => [
                        'self' => ['href' => 'http://v3.dbt.io/search/' . $manufactured_id]
                    ]
                ];

            case 'v3_books':
                $manufactured_id = random_int(0, 20000);
                return [
                    'id'           => (string) $manufactured_id,
                    'name'         => (string) $book->name,
                    'dam_id'       => (string) $book->bible_id,
                    'book_code'    => (string) $book->id,
                    'order'        => (string) $book->protestant_order,
                    'enabled'      => true,
                    'chapters'     => $book->chapters,
                    'chapter_list' => $book->chapters->pluck('number')->implode(','),
                    '_links'       => [
                        'self' => ['href' => 'http://v3.dbt.io/search/' . $manufactured_id]
                    ]
                ];
        }
        return [];
    }

    /**
     * @OA\Schema (
     *    type="array",
     *    schema="v4_bible_books_all",
     *    description="The books of the bible with codes",
     *    title="v4_bible_books_all",
     *  @OA\Xml(name="v4_bible_books_all"),
     *  @OA\Items(
     *          @OA\Property(property="id",                ref="#/components/schemas/Book/properties/id"),
     *          @OA\Property(property="id_usfx",           ref="#/components/schemas/Book/properties/id_usfx"),
     *          @OA\Property(property="id_osis",           ref="#/components/schemas/Book/properties/id_osis"),
     *          @OA\Property(property="book_order",        ref="#/components/schemas/Book/properties/protestant_order"),
     *          @OA\Property(property="testament_order",   ref="#/components/schemas/Book/properties/testament_order"),
     *          @OA\Property(property="book_testament",    ref="#/components/schemas/Book/properties/book_testament"),
     *          @OA\Property(property="book_group",        ref="#/components/schemas/Book/properties/book_group"),
     *          @OA\Property(property="chapters",          ref="#/components/schemas/Book/properties/chapters"),
     *          @OA\Property(property="verses",            ref="#/components/schemas/Book/properties/verses"),
     *          @OA\Property(property="name",              ref="#/components/schemas/Book/properties/name"),
     *     )
     *   )
     * )
     *
     * @OA\Schema (
     *    type="array",
     *    schema="v4_bible.books",
     *    description="The books of the bible with codes",
     *    title="v4_bible.books",
     *  @OA\Xml(name="v4_bible.books"),
     *  @OA\Items(
     *          @OA\Property(property="id",                ref="#/components/schemas/Book/properties/id"),
     *          @OA\Property(property="id_usfx",           ref="#/components/schemas/Book/properties/id_usfx"),
     *          @OA\Property(property="id_osis",           ref="#/components/schemas/Book/properties/id_osis"),
     *          @OA\Property(property="book_order",        ref="#/components/schemas/Book/properties/protestant_order"),
     *          @OA\Property(property="testament_order",   ref="#/components/schemas/Book/properties/testament_order"),
     *          @OA\Property(property="book_testament",    ref="#/components/schemas/Book/properties/book_testament"),
     *          @OA\Property(property="book_group",        ref="#/components/schemas/Book/properties/book_group"),
     *          @OA\Property(property="chapters",          ref="#/components/schemas/Book/properties/chapters"),
     *          @OA\Property(property="verses",            ref="#/components/schemas/Book/properties/verses"),
     *          @OA\Property(property="name",              ref="#/components/schemas/Book/properties/name"),
     *     )
     *   )
     * )
     *
     * @param $book
     *
     * @return array
     */
    public function transformForV4($book)
    {
        switch ($this->route) {
            case 'v4_bible_books_all':
                return [
                    'book_id'         => $book->id,
                    'book_id_usfx'    => $book->id_usfx,
                    'book_id_osis'    => $book->id_osis,
                    'name'            => $book->name,
                    'testament'       => $book->book_testament,
                    'testament_order' => $book->testament_order,
                    'book_order'      => $book->protestant_order,
                    'book_group'      => $book->book_group,
                    'chapters'        => $book->chapters,
                ];

            case 'v4_bible.books':
                $result = [
                    'book_id'         => $book->book->id,
                    'book_id_usfx'    => $book->book->id_usfx,
                    'book_id_osis'    => $book->book->id_osis,
                    'name'            => $book->name,
                    'testament'       => $book->book->book_testament,
                    'testament_order' => $book->book->testament_order,
                    'book_order'      => $book->book->protestant_order,
                    'book_group'      => $book->book->book_group,
                    'chapters'        => array_map('\intval', explode(',', $book->chapters)),
                ];
                if ($book->content_types) {
                    $result['content_types'] = $book->content_types;
                }
                return $result;

            case 'v4_bible_filesets.books':
            default:
                return [
                    'book_id'         => $book->id,
                    'book_id_usfx'    => $book->id_usfx,
                    'book_id_osis'    => $book->id_osis,
                    'name'            => $book->name,
                    'testament'       => $book->book_testament,
                    'testament_order' => $book->testament_order,
                    'book_order'      => $book->book_order_column,
                    'book_group'      => $book->book_group,
                    'chapters'        => array_map('\intval', explode(',', $book->chapters)),
                ];
        }
    }
}
