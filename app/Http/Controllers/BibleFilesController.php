<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Helpers\AWS\Bucket;

class BibleFilesController extends APIController
{

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
        if(!$this->api) return view('bibles.filesets.index');

        $filesets = BibleFileset::all();
        return $this->reply($filesets);
    }

	/**
	 *
	 */
	public function store()
    {

    }

	/**
	 * @param string $id
	 *
	 * @return mixed
	 */
	public function show(string $id)
    {
	    $urls = [];
    	$types = checkParam('types');

    	$acceptedTypes = ['audio','html','epub','pdf'];
    	foreach($types as $type) if(!in_array($type,$acceptedTypes)) return $this->replyWithError('The Provided Type Parameter is not Valid');

	    $bible = Bible::find($id);
	    if(!$bible) return $this->replyWithError('The Provided Bible ID Parameter is not Valid');

	    $filenames = $_GET['filenames'] ?? "";
	    $filenames = explode(",",$filenames);
	    $signer = $_GET['signer'] ?? 's3_fcbh';
	    $bucket = $_GET['bucket'] ?? "dbp-dev";
	    $expiry = $_GET['expiry'] ?? 5;

	    foreach($filenames as $filename) {
		    $filename = ltrim($filename, "/");
		    $paths = explode("/",$filename);
		    $urls["urls"][$paths[0]][$paths[1]][$paths[2]] = Bucket::signedUrl($filename,$signer,$bucket,$expiry);
	    }
	    return $this->reply($urls);


    }

	/**
	 * @param $id
	 */
	public function update($id)
    {
        //
    }

	/**
	 * @param $id
	 */
	public function destroy($id)
    {
        //
    }
}
