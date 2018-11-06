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
	 *
	 * @OA\Get(
	 *     path="/bible/equivalents",
	 *     tags={"Bibles"},
	 *     summary="Get a list of bible equivalents",
	 *     description="Fetch a list of bible equivalents filtered by Type, Organization, or Bible",
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
		$type     = checkParam('type', null, 'optional');
		$org_id   = checkParam('organization_id', null, 'optional');
		$bible_id = checkParam('bible_id', null, 'optional');

		// Fetch Bible Equivalents
		$bible_equivalents = BibleEquivalent::when($type, function ($q) use ($type) {
			$q->where('type', $type);
		})->when($org_id, function ($q) use ($org_id) {
			$q->where('organization_id', $org_id);
		})->when($bible_id, function ($q) use ($bible_id) {
			$q->where('bible_id', $bible_id);
		})->get();

		if(!$bible_equivalents) return $this->setStatusCode(404)->replyWithError(trans('api.bible_equivalents_errors_404'));
		return $this->reply($bible_equivalents);
	}

	/**
	 *
	 * Get the list of equivalents for a single bible
	 *
	 * @link https://api.dbp.test/bibles/AAKWBT/equivalents?key=TEST_KEY&v=4&pretty
	 *
	 * @OA\Get(
	 *     path="/bibles/{id]/equivalents",
	 *     tags={"Bibles"},
	 *     summary="Returns Audio File path information",
	 *     description="This call returns the Equivalents for a specific Bible",
	 *     operationId="v4_bible.equivalents",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", description="The abbreviated `Bible` id", required=true, @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleEquivalent")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/BibleEquivalent")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/BibleEquivalent"))
	 *     )
	 * )
	 *
	 * @param $id
	 * @return Response
	 */
    public function show($id)
    {
    	$bible_equivalent = BibleEquivalent::where('bible_id',$id)->get();
    	if(!$bible_equivalent) return $this->setStatusCode(404)->replyWithError(trans('api.bible_equivalents_errors_404'));
    	return $this->reply($bible_equivalent);
    }
}
