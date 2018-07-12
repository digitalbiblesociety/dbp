<?php

namespace App\Http\Controllers\V2Controllers\LibraryCatalog;

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
	 * @OAS\Get(
	 *     path="/library/metadata",
	 *     tags={"Library Catalog"},
	 *     summary="This returns copyright and associated organizations info.",
	 *     description="",
	 *     operationId="v2_library_metadata",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OAS\MediaType(mediaType="text/yaml",        @OAS\Schema(ref="#/components/schemas/v2_library_metadata")),
	 *         @OAS\MediaType(mediaType="text/csv",         @OAS\Schema(ref="#/components/schemas/v2_library_metadata"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v2_library_metadata",
	 *     description="The various version ids in the old version 2 style",
	 *     title="v2_library_version",
	 *     @OAS\Xml(name="v2_library_version"),
	 *     @OAS\Property(property="dam_id",         ref="#/components/schemas/BibleFileset/id"),
	 *     @OAS\Property(property="mark",           ref="#/components/schemas/BibleFilesetCopyright/copyright"),
	 *     @OAS\Property(property="volume_summary", ref="#/components/schemas/BibleFilesetCopyright/copyright_description"),
	 *     @OAS\Property(property="organization", type="object",
	 *     @OAS\AdditionalProperties(
	 *         type="object",
	 *         @OAS\Property(property="organization_id",       ref="#/components/schemas/Organization/id"),
	 *         @OAS\Property(property="organization",          ref="#/components/schemas/Organization/name"),
	 *         @OAS\Property(property="organization_english",  ref="#/components/schemas/Organization/name"),
	 *         @OAS\Property(property="organization_role",     ref="#/components/schemas/Organization/role"),
	 *         @OAS\Property(property="organization_url",      ref="#/components/schemas/Organization/url"),
	 *         @OAS\Property(property="organization_donation", ref="#/components/schemas/Organization/donation"),
	 *         @OAS\Property(property="organization_address",  ref="#/components/schemas/Organization/address"),
	 *         @OAS\Property(property="organization_address2", ref="#/components/schemas/Organization/address2"),
	 *         @OAS\Property(property="organization_city",     ref="#/components/schemas/Organization/city"),
	 *         @OAS\Property(property="organization_state",    ref="#/components/schemas/Organization/state"),
	 *         @OAS\Property(property="organization_country",  ref="#/components/schemas/Organization/country"),
	 *         @OAS\Property(property="organization_zip",      ref="#/components/schemas/Organization/zip"),
	 *         @OAS\Property(property="organization_phone",    ref="#/components/schemas/Organization/phone")
	 *     )),
	 * )
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
