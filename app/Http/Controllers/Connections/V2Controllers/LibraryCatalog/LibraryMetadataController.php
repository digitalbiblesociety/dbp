<?php

namespace App\Http\Controllers\Connections\V2Controllers\LibraryCatalog;

use Illuminate\Http\Request;
use Cache;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFileset;
use App\Transformers\V2\LibraryCatalog\LibraryMetadataTransformer;

class LibraryMetadataController extends APIController
{
	/**
	 *
	 * @link https://api.dbp.dev/library/metadata?key=1234&pretty&v=2
	 *
	 * @OA\Get(
	 *     path="/library/metadata",
	 *     tags={"Library Catalog"},
	 *     summary="This returns copyright and associated organizations info.",
	 *     description="",
	 *     operationId="v2_library_metadata",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OA\MediaType(mediaType="text/yaml",        @OA\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v2_library_metadata"))
	 *     )
	 * )
	 *
	 *
	 * @return mixed
	 */
	public function index()
	{
		if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');

		$fileset_id = checkParam('dam_id', null, 'optional');
		$bucket_id  = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');

		Cache::forget('v2_library_metadata' . $fileset_id);
		$metadata = Cache::remember('v2_library_metadata' . $fileset_id, 1600, function () use ($fileset_id, $bucket_id) {

				$metadata = BibleFileset::with('copyright.organizations', 'copyright.role.roleTitle', 'bible.language')
					->has('bible.language')->has('copyright')
					->when($fileset_id, function ($q) use ($fileset_id) {
						$q->where('id', $fileset_id)->orWhere('id',substr($fileset_id,0,-4))->orWhere('id',substr($fileset_id,0,-2));
					})
					->where('bucket_id', $bucket_id)->get();

				$metadata->map(function($fileset) {
					$fileset->v2_id = strtoupper($fileset->bible->first()->language->iso.substr($fileset->bible->first()->id,3,3));
					return $fileset;
				});

				if(count($metadata) == 0) return $this->setStatusCode(404)->replyWithError("Missing metadata");
				if(count($metadata) == 1) return [fractal($metadata[0], new LibraryMetadataTransformer())->serializeWith($this->serializer)];
				return fractal($metadata, new LibraryMetadataTransformer())->serializeWith($this->serializer);
			});

		return $this->reply($metadata);
	}




}
