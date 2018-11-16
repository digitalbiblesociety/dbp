<?php

namespace App\Http\Controllers\Connections\V2Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Http\Controllers\APIController;

class VerseController extends APIController
{
    /**
     * This function handles the library/verseinfo route
     * for backwards compatibility with v2. Lacking a
     * transformer as it's essentially depreciated
     *
     *
     * @version 2
     * @category v2_library_book
     * @category v2_library_bookOrder
     * @link https://dbt.io/library/verseinfo?key=TEST_KEY&v=2&dam_id=ENGKJV&book_id=GEN&chapter=1&verse_start=11 - V2 Access
     * @link https://api.dbp.test/library/verseinfo?key=TEST_KEY&v=2&dam_id=ENGKJV&book_id=GEN&chapter=1&verse_start=11 - V2 Test
     * @link https://dbp.test/eng/docs/swagger/v2#/Library/v2_library_verseinfo - V2 Test Docs
     *
     * @OA\Get(
     *     path="/library/verseinfo",
     *     tags={"Library Catalog"},
     *     summary="Returns Library File path information",
     *     description="This method retrieves the bible verse info for the specified volume/book/chapter.",
     *     operationId="v2_library_verseinfo",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="dam_id", in="query", description="the DAM ID of the verse info", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="book_id", in="path", description="If specified returns verse text ONLY for the specified book", required=true, @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Parameter(name="chapter", in="path", description=" If specified returns verse text ONLY for the specified chapter", required=true, @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Parameter(name="verse_start", in="path", description="Returns all verse text for the specified book, chapter, and verse range from 'verse_start' until either the end of chapter or 'verse_end'", required=true, @OA\Schema(ref="#/components/schemas/BibleFile/properties/verse_start")),
     *     @OA\Parameter(name="verse_end", in="path", description="If specified returns of all verse text for the specified book, chapter, and verse range from 'verse_start' to 'verse_end'.", required=false, @OA\Schema(ref="#/components/schemas/BibleFile/properties/verse_end")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_asset")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/v2_library_asset"))
     *     )
     * )
     *
     * @return mixed
     */
    public function info()
    {
        $bible_id    = checkParam('dam_id');
        $book_id     = checkParam('book_id');
        $chapter_id  = checkParam('chapter|chapter_id');
        $verse_start = checkParam('verse_start');
        $verse_end   = checkParam('verse_end', null, 'optional');

        $bible = Bible::find($bible_id);
        $book  = Book::where('id', $book_id)->orWhere('id_usfx', $book_id)->first();
        if (!$book) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_books_errors_404'));
        }

        $verse_info = \DB::connection('sophia')->table($bible->id . '_vpl')->where([
            ['book', '=', $book->id_usfx],
            ['chapter', '=', $chapter_id],
            ['verse_start', '>=', $verse_start],
        ])->when($verse_end, function ($query) use ($verse_end) {
            return $query->where('verse_start', '<=', $verse_end);
        })->select([
            'book as book_id',
            'chapter as chapter_number',
            'verse_start',
            'verse_end',
            'verse_text',
            'canon_order as id',
        ])->get();
        foreach ($verse_info as $key => $verse) {
            $verse_info[$key]->bible_id           = $bible->id;
            $verse_info[$key]->bible_variation_id = null;
        }

        return $this->reply($verse_info);
    }
}
