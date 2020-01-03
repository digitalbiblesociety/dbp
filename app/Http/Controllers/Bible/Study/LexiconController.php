<?php

namespace App\Http\Controllers\Bible\Study;

use App\Http\Controllers\APIController;
use App\Models\Bible\Study\Lexicon;

class LexiconController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     *
     * @OA\Get(
     *     path="/lexicons",
     *     tags={"StudyBible"},
     *     summary="",
     *     description="",
     *     operationId="v4_lexicon_index",
     *     @OA\Parameter(
     *         name="word",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/Lexicon/properties/base_word"),
     *         description="The english word of the greek or hebrew source to filter results by",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         @OA\Schema(type="string",enum={"G", "H"}),
     *         description="The language to filter by, either greek or hebrew. This effectively serves as a testament filter"
     *     ),
     *     @OA\Parameter(
     *         name="exact_match",
     *         in="query",
     *         @OA\Schema(type="boolean"),
     *         description="Enables"
     *     ),
     *     @OA\Parameter(name="limit",  in="query", description="The Number of records to return", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="application/toml", @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="text/csv",  @OA\Schema(ref="#/components/schemas/v4_lexicon_index"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="array",
     *     schema="v4_lexicon_index",
     *     title="The lexicon response",
     *     @OA\Xml(name="v4_lexicon_index"),
     *     @OA\Items(
     *          @OA\Property(property="id",             ref="#/components/schemas/Lexicon/properties/id"),
     *          @OA\Property(property="base_word",      ref="#/components/schemas/Lexicon/properties/base_word"),
     *          @OA\Property(property="usage",          ref="#/components/schemas/Lexicon/properties/usage"),
     *          @OA\Property(property="definition",     ref="#/components/schemas/Lexicon/properties/definition"),
     *          @OA\Property(property="derived",        ref="#/components/schemas/Lexicon/properties/derived"),
     *          @OA\Property(property="part_of_speech", ref="#/components/schemas/Lexicon/properties/part_of_speech"),
     *          @OA\Property(property="aramaic",        ref="#/components/schemas/Lexicon/properties/aramaic"),
     *          @OA\Property(property="comment",        ref="#/components/schemas/Lexicon/properties/comment")
     *     )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $word        = checkParam('word', true);
        $language    = checkParam('language');
        $exact_match = checkParam('exact_match');
        $limit       = checkParam('limit');

        return $this->reply(Lexicon::filterByLanguage($language)->filterByWord($word, $exact_match)->take($limit));
    }
}
