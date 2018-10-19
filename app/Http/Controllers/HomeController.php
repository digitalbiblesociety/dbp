<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFileset;
use App\Models\Country\Country;
use App\Models\Country\CountryLanguage;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Bible\Bible;
use App\Helpers\AWS\Bucket;
use App\Models\Resource\Resource;
use App\Traits\CallsBucketsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends APIController
{

	use CallsBucketsTrait;

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = \Auth::user();

		return view('home', compact('user'));
	}

	public function admin()
	{
		$status['updates'] = '';

		return view('dashboard.admin', compact('status'));
	}

	/**
	 * Returns a List of Buckets used by the API
	 *
	 * @OA\Get(
	 *     path="/api/buckets",
	 *     tags={"Bibles"},
	 *     summary="Returns aws buckets currently being used by the api",
	 *     description="",
	 *     operationId="v4_api.buckets",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_api_buckets")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_api_buckets")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_api_buckets"))
	 *     )
	 * )
	 *
	 * @OA\Schema (
	 *     type="object",
	 *     schema="v4_api_buckets",
	 *     description="The aws buckets currently being used by the api",
	 *     title="The buckets response",
	 *     required={"id","organization_id"},
	 *     @OA\Xml(name="v4_api_buckets"),
	 *     @OA\Property(property="id",              ref="#/components/schemas/Bucket/properties/id"),
	 *     @OA\Property(property="organization_id", ref="#/components/schemas/Bucket/properties/organization_id")
	 * )
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
		$count['languages']     = Language::count();
		$count['countries']     = Country::count();
		$count['alphabets']     = Alphabet::count();
		$count['organizations'] = Organization::count();
		$count['bibles']        = Bible::count();

		return view('welcome', compact('count'));
	}

	public function stats()
    {
        $count['languages']      = Language::count();
        $count['countries']      = Country::count();
        $count['alphabets']      = Alphabet::count();
        $count['organizations']  = Organization::count();
        $count['bible_filesets'] = BibleFileset::count();
        $count['bibles']         = Bible::count();
        $count['resources']      = Resource::count();

        return $this->reply($count);
    }

	public function versions()
	{
		return $this->reply(["versions" => [2, 4]]);
	}

	public function signedUrls()
	{
		$filenames = $_GET['filenames'] ?? "";
		$filenames = explode(",", $filenames);
		$signer    = $_GET['signer'] ?? 's3_fcbh';
		$bucket    = $_GET['bucket'] ?? "dbp-dev";
		$expiry    = $_GET['expiry'] ?? 5;
		$urls      = [];

		$transaction_id = random_int(0,10000000);
		foreach ($filenames as $filename) {
			$filename                                      = ltrim($filename, "/");
			$paths                                         = explode("/", $filename);
			$urls["urls"][$paths[0]][$paths[1]][$paths[2]] = $this->signedUrl($filename, $signer, $bucket, $transaction_id);
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

	public function error($status = null,$message = "")
	{
		if($status) return view('errors.'.$status,compact('message'));
		return view('errors.broken',compact('message'));
	}

}
