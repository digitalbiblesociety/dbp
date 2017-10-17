<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVariation;
use App\Models\Bible\Book;
use App\Transformers\FileSetTransformer;
use Illuminate\Http\Request;
use ZipArchive;
use App\Jobs\ProcessBible;

class BibleFileSetsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSON|View
     */
    public function overview(string $id)
    {
        return view('bibles.filesets.overview', compact('bibleVariation'));
    }

    public function index()
    {
    	if(!$this->api) return view('bibles.filesets.index');

	    $filesets = BibleFileset::all();
	    return $this->reply(fractal()->collection($filesets)->transformWith(FileSetTransformer::class)->serializeWith($this->serializer));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$bibles = Bible::with('currentTranslation')->select('id')->get()->pluck('currentTranslation.name','id');
        return view('bibles.filesets.create', compact('bibles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    ini_set('upload_max_filesize', '1000M');
	    ini_set('post_max_size', '1000M');
	    ini_set('max_input_time', 3000);
	    ini_set('max_execution_time', 3000);

	    $fileset = new BibleFileset();
	    $fileset->id = $request->id;
	    $fileset->name = $request->name;
	    $fileset->set_type = $request->set_type;
	    $fileset->organization_id = $request->organization_id;
	    $fileset->variation_id = $request->variation_id;
	    $fileset->bible_id = $request->bible;
	    $fileset->save();

	    $bible = $request->file('file');
	    $zip = new ZipArchive;
	    $res = $zip->open($bible);
	    if ($res === TRUE) {
	    	$path = storage_path("bibles/input/");
	    	if(!file_exists($path)) mkdir($path);
		    $zip->extractTo($path);
		    $zip->close();
	    }

	    exec("python /Sites/dbp/aletheia/processing/usfm2epub.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/bibles/ /Sites/dbp/aletheia/");
	    exec("python /Sites/dbp/aletheia/processing/usfm2html.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/bibles/ /Sites/dbp/aletheia/");
	    exec("python /Sites/dbp/aletheia/processing/usfm2inscript.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/bibles/ /Sites/dbp/aletheia/");
	    exec("python /Sites/dbp/aletheia/processing/usfm2dbp.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/bibles/ /Sites/dbp/aletheia/");

	    // ProcessBible::dispatch($request->file('zip'), $fileset->id);
	    return view('bibles.filesets.thanks', compact('fileset'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $fileset = BibleFileset::find($id);
        return view('bibles.filesets.show', compact('fileset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$fileset = BibleFileset::find($id);
        return view('bibles.filesets.edit', compact('fileset'));
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
	    $fileset = BibleFileset::find($id);
	    return view('bibles.filesets.thanks', compact('fileset'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $fileset = BibleFileset::find($id);
	    return view('bibles.filesets.thanks', compact('fileset'));
    }
}
