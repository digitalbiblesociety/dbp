<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleEquivalent;
use Illuminate\Http\Response;

class BibleEquivalentsController extends APIController
{

    /**
     *
     * @link https://api.dbp.test/bibles/equivalents?key=1234&v=4&pretty
     * @OA\Get(
     *     path="/bible/equivalents",
     *     tags={"Bibles"},
     *     summary="Get a list of bible equivalents",
     *     description="Fetch a list of bible equivalents filtered by Type, Organization or Bible.
     *         This route will allow your apps to connect to other Bible APIs and services without
     *         introducing duplicate Bible content into your apps and ease migration between APIs.",
     *     operationId="v4_bible_equivalents.all",
     *     @OA\Parameter(
     *       name="organization_id",
     *       in="query",
     *       description="The organization id to filter equivalents by",
     *       @OA\Schema(ref="#/components/schemas/Organization/properties/id")
     *     ),
     *     @OA\Parameter(
     *        name="bible_id",
     *        in="query",
     *        description="The Bible id to return equivalents for",
     *        @OA\Schema(ref="#/components/schemas/Bible/properties/id")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible_equivalents.all")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible_equivalents.all")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible_equivalents.all")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_bible_equivalents.all"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="array",
     *     schema="v4_bible_equivalents.all",
     *     description="v4_bible_equivalents.all",
     *     title="v4_bible_equivalents.all",
     *     @OA\Xml(name="v4_bible_equivalents.all"),
     *     @OA\Items(
     *      ref="#/components/schemas/BibleEquivalent"
     *    )
     * )
     *
     * @return Response
     */
    public function index()
    {
        // Check Params
        $org_id   = checkParam('organization_id');
        $bible_id = checkParam('bible_id');

        // Fetch Bible Equivalents
        $cache_string = strtolower('bible_equivalents:'.$org_id.$bible_id);
        $bible_equivalents = \Cache::remember($cache_string, now()->addDay(), function () use ($org_id, $bible_id) {
            return BibleEquivalent::when($org_id, function ($q) use ($org_id) {
                $q->where('organization_id', $org_id);
            })->when($bible_id, function ($q) use ($bible_id) {
                $q->where('bible_id', $bible_id);
            })->get();
        });
        
        if ($bible_equivalents->count() === 0) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_equivalents_errors_404'));
        }
        return $this->reply($bible_equivalents);
    }
}
