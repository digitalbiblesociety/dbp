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

// for download
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class BibleFileSetsController extends APIController
{

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
    {
	    if(!$this->api) return view('bibles.filesets.index');
	    $bible_id = CheckParam('dam_id',$id) ?? CheckParam('fileset_id',$id) ;
	    $chapter_id = CheckParam('chapter_id',null,'optional');
	    $book_id = CheckParam('book_id',null,'optional');
	    $driver = CheckParam('bucket',null,'optional') ?? "s3_fcbh";
	    $lifespan = CheckParam('lifespan',null,'optional') ?? 5;
	    if($book_id) $book = Book::where('id',$book_id)->orWhere('id_osis',$book_id)->orWhere('id_usfx',$book_id)->first();
	    if(isset($book)) $book_id = $book->id;
		$fileset = BibleFileset::with('meta')->find($bible_id);
		$fileset_type = (strpos(strtolower($fileset->set_type), 'audio') !== false) ? 'audio' : 'text';
	    $fileSetChapters = BibleFile::with('book.currentTranslation')
		    ->join('books', 'books.id', '=', 'bible_files.book_id')
			->where('hash_id',$fileset->hash_id)
			->when($chapter_id, function ($query) use ($chapter_id) {
			    return $query->where('chapter_start', $chapter_id);
			})->when($book_id, function ($query) use ($book_id) {
			    return $query->where('book_id', $book_id);
		    })->orderBy('books.book_order')->orderBy('chapter_start')->get();

	    foreach ($fileSetChapters as $key => $fileSet_chapter) {
			$fileSetChapters[$key]->file_name = Bucket::signedUrl($fileset_type.'/'.$fileset->bible_id.'/'.$fileset->id.'/'.$fileSet_chapter->file_name, $driver, "dbp_dev", $lifespan);
	    }
	    return $this->reply(fractal()->collection($fileSetChapters)->serializeWith($this->serializer)->transformWith(new FileSetTransformer()));
    }

    public function download($id)
    {
	    $set_id = CheckParam('fileset_id',$id);
	    $books = CheckParam('book_ids',null,'optional');

	    $fileset = BibleFileset::where('id',$set_id)->first();
	    if(!$fileset) return $this->replyWithError("Fileset ID not found");

	    // Filter Download By Books
	    if($books) {
	    	$books = explode(',',$books);
		    $files = BibleFile::with('book')->where('set_id',$fileset->id)->whereIn('book_id',$books)->get();
		    $books = $files->map(function ($file) {
		    	$testamentLetter = ($file->book->book_testament == "NT") ? "B" : "A";
			    return $testamentLetter.str_pad($file->book->testament_order, 2, 0, STR_PAD_LEFT);
		    })->unique();
	    }
	    Bucket::download($files,'s3_fcbh', 'dbp_dev', 5, $books);
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
