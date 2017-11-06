<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Transformers\FileSetTransformer;
use ZipArchive;

class BibleFileSetsController extends APIController
{

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
    	if(!$this->api) return view('bibles.filesets.index');

	    $filesets = BibleFileset::all();
	    return $this->reply(fractal()->collection($filesets)->transformWith(FileSetTransformer::class)->serializeWith($this->serializer));
    }


	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
    {
    	$bibles = Bible::with('currentTranslation')->select('id')->get()->pluck('currentTranslation.name','id');
        return view('bibles.filesets.create', compact('bibles'));
    }

	/**
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function store()
    {
	    ini_set('upload_max_filesize', '1000M');
	    ini_set('post_max_size', '1000M');
	    ini_set('max_input_time', 3000);
	    ini_set('max_execution_time', 3000);

	    $fileset = BibleFileset::create(request()->all());

	    $bible = request()->file('file');
	    $zip = new ZipArchive;
	    $res = $zip->open($bible);
	    if ($res === TRUE) {
	    	$path = storage_path("bibles/input/");
	    	if(!file_exists($path)) mkdir($path);
		    $zip->extractTo($path);
		    $zip->close();
	    }

	    exec("python /Sites/dbp/aletheia/processing/usfm2epub.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/scriptures/ /Sites/dbp/aletheia/");
	    exec("python /Sites/dbp/aletheia/processing/usfm2html.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/scriptures/ /Sites/dbp/aletheia/");
	    exec("python /Sites/dbp/aletheia/processing/usfm2inscript.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/scriptures/ /Sites/dbp/aletheia/");
	    exec("python /Sites/dbp/aletheia/processing/usfm2dbp.py $fileset->id /Sites/dbp/storage/bibles/input/ /Sites/dbp/public/scriptures/ /Sites/dbp/aletheia/");

	    // ProcessBible::dispatch($request->file('zip'), $fileset->id);
	    return view('bibles.filesets.thanks', compact('fileset'));
    }

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function show($id)
    {
	    $fileset = BibleFileset::with('files')->find($id);
        return view('bibles.filesets.show', compact('fileset'));
    }

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($id)
    {
    	$fileset = BibleFileset::find($id);
        return view('bibles.filesets.edit', compact('fileset'));
    }

	/**
	 * TODO: Validation and Save
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function update($id)
    {
	    $fileset = BibleFileset::find($id);
	    return view('bibles.filesets.thanks', compact('fileset'));
    }

	/**
	 * TODO: Validation and Save
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function destroy($id)
    {
	    $fileset = BibleFileset::find($id);
	    return view('bibles.filesets.thanks', compact('fileset'));
    }
}
