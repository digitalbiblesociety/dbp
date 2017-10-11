<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use Illuminate\Http\Request;

class BibleFilesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

	/**
	 * Return the Signed URL for Bible Files
	 *
	 * @param string $id
	 */
	public function show(string $id)
    {
    	$types = checkParam('types');
	    $references = checkParam('references');

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
