<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleEquivalent;

class BibleEquivalentsController extends APIController
{


	/**
	 *
	 * Get the list of equivalents filtered by type or organization
	 *
	 * @link https://api.dbp.test/bibles/equivalents?key=1234&v=4&pretty
	 *
	 * @OA\Get(
	 *     path="/bible/equivalents",
	 *     tags={"Bibles"},
	 *     summary="Get a list of bible equivalents",
	 *     description="Get a list of bible equivalents filtered by type or organization",
	 *     operationId="v4_bible.equivalents",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", description="The abbreviated `Bible` id", required=true, @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleEquivalent"))
	 *     )
	 * )
	 *
	 * @return json
	 */
	public function index()
	{
		$type     = checkParam('type',null,'optional');
		$org_id   = checkParam('organization_id',null,'optional');
		$bible_id = checkParam('bible_id',null,'optional');

		$bible_equivalents = BibleEquivalent::when($type, function ($q) use ($type) {
			$q->where('type', $type);
		})->when($org_id, function ($q) use ($org_id) {
			$q->where('type', $org_id);
		})->when($bible_id, function ($q) use ($bible_id) {
			$q->where('bible_id', $bible_id);
		})->get();

		return $this->reply($bible_equivalents);
	}

	/**
	 *
	 * Get the list of equivalents for a single bible
	 *
	 * @link https://api.dbp.test/bibles/AAKWBT/equivalents?key=1234&v=4&pretty
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
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleEquivalent"))
	 *     )
	 * )
	 *
	 * @return json
	 */
    public function show($id)
    {
    	$bible_equivalents = BibleEquivalent::where('bible_id',$id)->get();
    	return $this->reply($bible_equivalents);
    }
}
