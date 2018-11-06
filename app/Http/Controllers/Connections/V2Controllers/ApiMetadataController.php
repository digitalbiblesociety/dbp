<?php

namespace App\Http\Controllers\Connections\V2Controllers;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFileset;
use App\Models\Organization\Asset;
use App\Traits\CallsBucketsTrait;
use Storage;

class ApiMetadataController extends APIController
{

	use CallsBucketsTrait;

	/**
	 *
	 *
	 * @param null    $path1
	 * @param null    $path2
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function passThrough($path1 = null,$path2 = null)
	{
		$params = checkParam('params',null,'optional') ?? $_GET;
		if(\is_array($params)) {
			$params = implode('&', array_map(function ($v, $k) {
				if($k === 'key') return 'key=53355c32fca5f3cac4d7a670d2df2e09';
				if($k === 0) return $v;
				return sprintf('%s=%s', $k, $v);
			}, $params, array_keys($params)));
		}
		$contents = json_decode(file_get_contents('https://dbt.io/'.$path1.'/'.$path2.'?'.$params));
		return response()->json($contents);
	}

	/**
	 *
	 * Returns an array of signed audio urls
	 *
	 * @category v2_library_asset
	 * @link http://api.dbp4.org/library/asset - V4 Access
	 * @link https://api.dbp.test/library/asset?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_library_asset - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/library/asset",
	 *     tags={"Library Catalog"},
	 *     summary="Returns Library File path information",
	 *     description="This call returns the file path information. This information can be used with the response of the locations calls to create a URI to retrieve files.",
	 *     operationId="v2_library_asset",
	 *     @OA\Parameter(name="dam_id", in="query", description="The DAM ID for which to retrieve file path info.", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_asset")),
	 *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/v2_library_asset"))
	 *     )
	 * )
	 *
	 * @OA\Schema (
	 *     type="object",
	 *     schema="v2_library_asset",
	 *     description="v2_library_asset",
	 *     title="v2_library_asset",
	 *     @OA\Xml(name="v2_library_asset"),
	 *     @OA\Property(property="server",type="string",example="cloud.faithcomesbyhearing.com"),
	 *     @OA\Property(property="root_path",type="string",example="/mp3audiobibles2"),
	 *     @OA\Property(property="protocol",type="string",example="http"),
	 *     @OA\Property(property="CDN",type="string",example="1"),
	 *     @OA\Property(property="priority",type="string",example="5"),
	 *     @OA\Property(property="volume_id",type="string",example="")
	 * )
	 *
	 * @return mixed
	 */
	public function libraryAsset()
	{
		$dam_id   = checkParam('dam_id|fileset_id', null, 'optional');
		$asset_id = checkParam('bucket|bucket_id|asset_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$protocol = checkParam('protocol', null, 'optional');

		if($dam_id) {
			$fileset = BibleFileset::where('id',$dam_id)->orWhere('id',substr($dam_id,0,-4))->orWhere('id',substr($dam_id,0,-2))->where('asset_id', $asset_id)->first();
			if(!$fileset) return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404'));
		}

		$asset = Asset::where('id',$asset_id)->first();

		$libraryAsset = [
			[
				'server'    => $asset->base_name,
				'root_path' => '/audio',
				'protocol'  => $asset->protocol,
				'CDN'       => $asset->cdn,
				'priority'  => $asset->priority,
				'volume_id' => $dam_id,
			]
		];

		return $this->reply($libraryAsset);
	}

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_video_path
	 * @link http://api.dbp4.org/api/reply - V4 Access
	 * @link https://api.dbp.test/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/api/apiversion",
	 *     tags={"API"},
	 *     summary="Returns version information",
	 *     description="Gives information about return types of the different versions of the APIs",
	 *     operationId="v2_api_versionLatest",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_api_versionLatest")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_api_versionLatest"))
	 *     )
	 * )
	 *
	 * @OA\Schema (
	 *     type="object",
	 *     schema="v2_api_versionLatest",
	 *     description="The return for the api reply",
	 *     title="v2_api_versionLatest",
	 *     @OA\Xml(name="v2_api_apiReply"),
	 *     @OA\Property(property="Version",type="string",example="2.0.0"),
	 * )
	 *
	 * @return mixed
	 */
	public function versionLatest()
	{
		return $this->reply(['Version' => 4]);
	}

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_api_apiReply
	 * @link http://api.dbp4.org/api/reply - V4 Access
	 * @link https://api.dbp.test/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/api/reply",
	 *     tags={"API"},
	 *     summary="Returns version information",
	 *     description="Gives information about return types of the different versions of the APIs",
	 *     operationId="v2_api_apiReply",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_api_apiReply")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_api_apiReply"))
	 *     )
	 * )
	 *
	 * @OA\Schema (
	 *     type="object",
	 *     schema="v2_api_apiReply",
	 *     description="The return for the api reply",
	 *     title="v2_api_apiReply",
	 *     @OA\Xml(name="v2_api_apiReply"),
	 *     example={"json", "jsonp", "html"}
	 * )
	 *
	 * @return mixed
	 */
	public function versionReplyTypes()
	{
		$versionReplies = [
			'2' => ['json', 'jsonp', 'html'],
			'4' => ['json', 'jsonp', 'xml', 'html'],
		];

		return $this->reply($versionReplies[$this->v]);
	}

}
