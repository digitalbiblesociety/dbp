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
               This route will allow your apps to connect to other Bible APIs and services without
               introducing duplicate Bible content into your apps and ease migration between APIs.",
     *     operationId="v4_bible.equivalents",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleEquivalent")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/BibleEquivalent")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/BibleEquivalent"))
     *     )
     * )
     *
     * @return Response
     */
    public function index()
    {
        // Check Params
        $type     = checkParam('type');
        $org_id   = checkParam('organization_id');
        $bible_id = checkParam('bible_id');

        // Fetch Bible Equivalents
        $bible_equivalents = BibleEquivalent::when($type, function ($q) use ($type) {
            $q->where('type', $type);
        })->when($org_id, function ($q) use ($org_id) {
            $q->where('organization_id', $org_id);
        })->when($bible_id, function ($q) use ($bible_id) {
            $q->where('bible_id', $bible_id);
        })->get();

        if (!$bible_equivalents) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_equivalents_errors_404'));
        }
        return $this->reply($bible_equivalents);
    }
}
