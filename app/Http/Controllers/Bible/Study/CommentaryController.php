<?php

namespace App\Http\Controllers\Bible\Study;

use App\Http\Controllers\APIController;
use App\Models\Bible\Study\Commentary;
use App\Models\Bible\Study\CommentarySection;

class CommentaryController extends APIController
{

    /**
     *
     * @OA\Get(
     *     path="/commentaries",
     *     tags={"StudyBible"},
     *     summary="Commentaries",
     *     description="A list of all the commentaries that can be retrieved",
     *     operationId="v4_commentary_index",
     *     @OA\Response(
     *         response=200,
     *         description="The fileset types",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_commentary_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_commentary_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",  @OA\Schema(ref="#/components/schemas/v4_commentary_index")),
     *         @OA\MediaType(mediaType="text/csv",  @OA\Schema(ref="#/components/schemas/v4_commentary_index"))
     *     )
     * )
     *
     * @OA\Schema(
     *   schema="v4_commentary_index",
     *   type="object",
     *   @OA\Property(property="data", type="array",
     *      @OA\Items(ref="#/components/schemas/Commentary")
     *   )
     * )
     *
     */
    public function index()
    {
        $commentaries = Commentary::with('translations')->get();
        return $this->reply(['data' => $commentaries]);
    }

    /**
     *
     * @OA\Get(
     *     path="/commentaries/{commentary_id}/chapters",
     *     tags={"StudyBible"},
     *     summary="Commentary Chapters",
     *     description="A list of all the chapter navigation for a specific commentary",
     *     operationId="v4_commentary_chapter",
     *     @OA\Parameter(
     *          name="commentary_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Commentary/properties/id"),
     *          description="The id of the commentary"
     *     ),
     *     @OA\Parameter(name="book_id", in="query", description="Will filter the results by the given book.  For a complete list see the `book_id` field in the `/bibles/books` route.",
     *          @OA\Schema(ref="#/components/schemas/Book/properties/id")
     *     ),
     *     @OA\Parameter(name="chapter", in="query", description="Will filter the results by the given chapter",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The fileset types",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_commentaries_chapter_response")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/v4_commentaries_chapter_response")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v4_commentaries_chapter_response")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/v4_commentaries_chapter_response"))
     *     )
     * )
     *
     * @OA\Schema(
     *     type="object",
     *     title="The all alphabets response",
     *     description="",
     *     schema="v4_commentaries_chapter_response",
     *     @OA\Xml(name="v4_commentaries_chapter_response"),
     *     example={"data":{
     *       "MAT": {1,2,3,4,5},
     *       "MRK": {1,2,3},
     *       "LUK": {1,2,3,4,5,6,7,8,9},
     *       "JHN": {1,2,3,4,10}
     *     }}
     * )
     *
     * @param $commentary_id
     * @return mixed
     *
     */
    public function chapters($commentary_id)
    {
        $book_id = checkParam('book_id');
        $chapter = checkParam('chapter');

        $commentary = Commentary::with('translations')->get();
        $commentary_sections = CommentarySection::where('commentary_id', $commentary_id)->distinct()
            ->when($book_id, function ($query) use ($book_id) {
                $query->where('book_id', $book_id);
            })
            ->when($chapter, function ($query) use ($chapter) {
                $query->where('chapter_start', $chapter);
            })
            ->leftJoin('books', function ($query) {
                $query->on('books.id', 'commentary_sections.book_id');
            })->select('book_id', 'chapter_start')
            ->orderBy('books.protestant_order')
            ->orderBy('commentary_sections.chapter_start')->get();

        foreach ($commentary_sections as $section) {
            $books[$section->book_id][] = $section->chapter_start;
        }

        return $this->reply(['data' => $books, 'meta' => $commentary->toArray()]);
    }

    /**
     *
     * @OA\Get(
     *     path="/commentaries/{commentary_id}/{book_id}/{chapter}",
     *     tags={"StudyBible"},
     *     summary="Commentary Sections",
     *     description="A list of all the chapter navigation for a specific commentary",
     *     operationId="v4_commentary_section",
     *     @OA\Parameter(
     *          name="commentary_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Commentary/properties/id"),
     *          description="The commentary id of the commentary"
     *     ),
     *     @OA\Parameter(name="book_id", in="path", required=true, description="Will filter the results by the given book.  For a complete list see the `book_id` field in the `/bibles/books` route.",
     *          @OA\Schema(ref="#/components/schemas/Book/properties/id")
     *     ),
     *     @OA\Parameter(name="chapter", in="path", required=true, description="Will filter the results by the given chapter",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The fileset types",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_commentaries_section_response")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_commentaries_section_response")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v4_commentaries_section_response")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/v4_commentaries_section_response"))
     *     )
     * )
     *
     * @OA\Schema(
     *     type="object",
     *     title="The commentary section response",
     *     description="",
     *     schema="v4_commentaries_section_response",
     *     @OA\Xml(name="v4_commentaries_section_response"),
     *     @OA\Property(property="data", type="array",
     *      @OA\Items(
     *          @OA\Property(property="title",         ref="#/components/schemas/CommentarySection/properties/title"),
     *          @OA\Property(property="content",       ref="#/components/schemas/CommentarySection/properties/content"),
     *          @OA\Property(property="book_id",       ref="#/components/schemas/CommentarySection/properties/book_id"),
     *          @OA\Property(property="chapter_start", ref="#/components/schemas/CommentarySection/properties/chapter_start"),
     *          @OA\Property(property="chapter_end",   ref="#/components/schemas/CommentarySection/properties/chapter_end"),
     *          @OA\Property(property="verse_start",   ref="#/components/schemas/CommentarySection/properties/verse_start"),
     *          @OA\Property(property="verse_end",     ref="#/components/schemas/CommentarySection/properties/verse_end"),
     *      )
     *     )
     * )
     *
     * @param $commentary_id
     * @param $book_id
     * @param $chapter
     *
     * @return mixed
     */
    public function sections($commentary_id, $book_id, $chapter)
    {
        $commentary = Commentary::with('translations')->get();
        $commentary_section = CommentarySection::where([
            ['commentary_id', $commentary_id],
            ['book_id', $book_id],
            ['chapter_start', $chapter]
        ])->get();

        return $this->reply(['data' => $commentary_section, 'meta' => $commentary->toArray()]);
    }
}
