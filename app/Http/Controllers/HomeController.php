<?php

namespace App\Http\Controllers;

use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Bible\Bible;
use App\Helpers\AWS\Bucket;

class HomeController extends APIController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $user = \Auth::user();
        return view('home',compact('user'));
    }

    public function admin()
    {
    	$status['updates'] = '';

    	return view('dashboard.admin', compact('status'));
    }

	/**
	 *
	 * Returns a List of Buckets used by the API
	 *
	 * @return mixed
	 */
	public function buckets()
    {
    	return $this->reply(\App\Models\Organization\Bucket::with('organization')->get());
    }

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function welcome()
	{
		$count['languages'] = Language::count();
		$count['countries'] = Country::count();
		$count['alphabets'] = Alphabet::count();
		$count['organizations'] = Organization::count();
		$count['bibles'] = Bible::count();

		return view('welcome',compact('count'));
	}

	public function versions()
	{
		return $this->reply(["versions" => [2,4]]);
	}

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_video_path
	 * @link http://api.bible.build/api/reply - V4 Access
	 * @link https://api.dbp.dev/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/api/apiversion",
	 *     tags={"Version 2"},
	 *     summary="Returns version information",
	 *     description="Gives information about return types of the different versions of the APIs",
	 *     operationId="v2_api_versionLatest",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/responses/v2_api_apiReply")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/responses/v2_api_apiReply"))
	 *     )
	 * )
	 *
	 * @OAS\Response(
	 *   response="v2_api_versionLatest",
	 *   description="The return for the api reply",
	 *   @OAS\MediaType(
	 *     mediaType="application/json",
	 *     @OAS\Schema(
	 *        @OAS\Property(property="2",type="object",example={"json", "jsonp", "html"}),
	 *        @OAS\Property(property="4",type="object",example={"json", "jsonp", "xml", "html"}),
	 *     )
	 *   )
	 * )
	 *
	 * @return mixed
	 */
	public function versionLatest()
	{
		$swagger = json_decode(file_get_contents(public_path('swagger.json')));
		return $this->reply([ "Version" => $swagger->info->version ]);
	}

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_api_apiReply
	 * @link http://api.bible.build/api/reply - V4 Access
	 * @link https://api.dbp.dev/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/api/reply",
	 *     tags={"Version 2"},
	 *     summary="Returns version information",
	 *     description="Gives information about return types of the different versions of the APIs",
	 *     operationId="v2_api_apiReply",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/responses/v2_api_apiReply")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/responses/v2_api_apiReply"))
	 *     )
	 * )
	 *
	 * @OAS\Response(
	 *   response="v2_api_apiReply",
	 *   description="The return for the api reply",
	 *   @OAS\MediaType(
	 *     mediaType="application/json",
	 *     @OAS\Schema(
	 *        @OAS\Property(property="2",type="object",example={"json", "jsonp", "html"}),
	 *        @OAS\Property(property="4",type="object",example={"json", "jsonp", "xml", "html"}),
	 *     )
	 *   )
	 * )
	 *
	 * @return mixed
	 */
	public function versionReplyTypes()
	{
		$versionReplies = [
			"2" => ["json", "jsonp", "html"],
			"4" => ["json", "jsonp", "xml", "html"]
		];
		return $this->reply($versionReplies[$this->v]);
	}


	/**
	 *
	 * Returns an array of signed audio urls
	 *
	 * @category v2_library_asset
	 * @link http://api.bible.build/library/asset - V4 Access
	 * @link https://api.dbp.dev/library/asset?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_library_asset - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/library/asset",
	 *     tags={"Version 2"},
	 *     summary="Returns Library File path information",
	 *     description="This call returns the file path information. This information can be used with the response of the locations calls to create a URI to retrieve files.",
	 *     operationId="v2_library_asset",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="dam_id", in="query", description="The DAM ID for which to retrieve file path info.", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/responses/v2_library_asset")),
	 *         @OAS\MediaType(mediaType="application/xml", @OAS\Schema(ref="#/components/responses/v2_library_asset"))
	 *     )
	 * )
	 *
	 * @OAS\Response(
	 *   response="v2_library_asset",
	 *   description="The minimized alphabet return for the all alphabets route",
	 *   @OAS\MediaType(
	 *     mediaType="application/json",
	 *     @OAS\Schema(
	 *        @OAS\Property(property="server",type="string",example="cloud.faithcomesbyhearing.com"),
	 *        @OAS\Property(property="root_path",type="string",example="/mp3audiobibles2"),
	 *        @OAS\Property(property="protocol",type="string",example="http"),
	 *        @OAS\Property(property="CDN",type="string",example="1"),
	 *        @OAS\Property(property="priority",type="string",example="5"),
	 *        @OAS\Property(property="volume_id",type="string",example=""),
	 *     )
	 *   )
	 * )
	 *
	 * @return mixed
	 */
	public function libraryAsset()
	{
		$dam_id = checkParam('dam_id', null, 'optional') ?? "";

		$libraryAsset = [
			[
				"server" => "cloud.faithcomesbyhearing.com",
				"root_path" => "/mp3audiobibles2",
				"protocol" => "http",
				"CDN" => "1",
				"priority" => "5",
				"volume_id" => $dam_id
			],
			[
				"server" => "fcbhabdm.s3.amazonaws.com",
				"root_path" => "/mp3audiobibles2",
				"protocol" => "http",
				"CDN" => "0",
				"priority" => "6",
				"volume_id" => $dam_id
			],
			[
				"server" => "cdn.faithcomesbyhearing.com",
				"root_path" => "/cfx/st",
				"protocol" => "rtmp-amazon",
				"CDN" => "0",
				"priority" => "9",
				"volume_id" => $dam_id
			]
		];
		return $this->reply($libraryAsset);
	}

	public function signedUrls()
	{
		$filenames = $_GET['filenames'] ?? "";
		$filenames = explode(",",$filenames);
		$signer = $_GET['signer'] ?? 's3_fcbh';
		$bucket = $_GET['bucket'] ?? "dbp-dev";
		$expiry = $_GET['expiry'] ?? 5;
		$urls = [];

		foreach($filenames as $filename) {
			$filename = ltrim($filename, "/");
			$paths = explode("/",$filename);
			$urls["urls"][$paths[0]][$paths[1]][$paths[2]] = Bucket::signedUrl($filename,$signer,$bucket,$expiry);
		}
		return $this->reply($urls);
	}

	public function status_dbl()
	{
		// Fetch Current number of DBL scriptures
		// compare to existing equivalents to DBL
		// return any discrepancy as a to do item
		$status_dbl = '';
		return $status_dbl;
	}

	public function status_biblegateway()
	{
		$status_gateway = '';
		return $status_gateway;
	}

}
