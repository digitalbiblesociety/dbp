<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFile;
use App\Models\Bible\Book;
use App\Helpers\AWS\Bucket;
use App\Models\User\Access;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

use App\Transformers\FileSetTransformer;

class BibleFileSetsController extends APIController
{

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
    {
	    if(!$this->api) return view('bibles.filesets.index');

	    $bible_id = CheckParam('dam_id',$id);
	    $chapter_id = CheckParam('chapter_id',null,'optional');
	    $book_id = CheckParam('book_id',null,'optional');
	    $bucket = CheckParam('bucket',null,'optional') ?? "s3_fcbh";
	    if($book_id) $book = Book::where('id',$book_id)->orWhere('id_osis',$book_id)->orWhere('id_usfx',$book_id)->first();
	    if(isset($book)) $book_id = $book->id;
		$fileset = BibleFileset::find($bible_id);
		$fileset_type = (strpos(strtolower($fileset->set_type), 'audio') !== false) ? 'audio' : 'text';
	    $fileSetChapters = BibleFile::with('book.currentTranslation')->where('set_id',$bible_id)
	                                ->when($chapter_id, function ($query) use ($chapter_id) {
		                                return $query->where('chapter_start', $chapter_id);
	                                })->when($book_id, function ($query) use ($book_id) {
			    return $query->where('book_id', $book_id);
		    })->orderBy('file_name')->get();
	    foreach ($fileSetChapters as $key => $fileSet_chapter) {
			$fileSetChapters[$key]->file_name = Bucket::signedUrl($fileset_type.'/'.$bible_id.'/'.$fileSet_chapter->file_name, $bucket);
	    }
	    return $this->reply(fractal()->collection($fileSetChapters)->serializeWith($this->serializer)->transformWith(new FileSetTransformer()));
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
