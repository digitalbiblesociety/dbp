<?php

namespace App\Http\Controllers\Bible\Study;

use App\Http\Controllers\APIController;
use App\Models\Bible\Study\Lexicon;
use App\Models\Bible\Study\LexicalDefinition;


class LexiconController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     *
     * @OA\Get(
     *     path="/lexicons/",
     *     tags={"Study"},
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
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="application/yaml", @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="application/toml", @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_lexicon_index")),
     *         @OA\MediaType(mediaType="application/csv",  @OA\Schema(ref="#/components/schemas/v4_lexicon_index"))
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
