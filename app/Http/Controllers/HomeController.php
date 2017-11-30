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

	public function versionLatest()
	{
		$swagger = json_decode(file_get_contents(public_path('swagger.json')));
		return $this->reply([ "Version" => $swagger->info->version ]);
	}

	public function versionReplyFormats()
	{
		$versionReplies = [
			"2" => ["json", "jsonp", "html"],
			"4" => ["json", "jsonp", "xml", "html"]
		];
		return $this->reply($versionReplies[$this->v]);
	}

	public function libraryAsset()
	{
		$libraryAsset = json_decode(file_get_contents(public_path('static/library_asset.json')));
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
}
