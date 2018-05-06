<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFilesetType;
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
	 *
	 * @OAS\Get(
	 *     path="/bibles/filesets/{id?}/",
	 *     tags={"Version 4"},
	 *     summary="Returns Bibles Filesets",
	 *     description="Returns a list of bible filesets",
	 *     operationId="v4_bible_filesets.show",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="The fileset ID",
	 *         @OAS\Schema(ref="#/components/schemas/BibleFileSet/properties/id")
	 *     ),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_bible_filesets.show")
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function show($id = null)
    {
	    if(!$this->api) return view('bibles.filesets.index');
	    $bible_id = CheckParam('dam_id',$id) ?? CheckParam('fileset_id',$id) ;
	    $chapter_id = CheckParam('chapter_id',null,'optional');
	    $book_id = CheckParam('book_id',null,'optional');
	    $bucket_id = CheckParam('bucket_id', null, 'optional');
	    if(!$bucket_id) $bucket_id = CheckParam('bucket', null, 'optional') ?? "s3_fcbh";
	    $lifespan = CheckParam('lifespan',null,'optional') ?? 5;
	    $type = checkParam('type');

	    if($book_id) $book = Book::where('id',$book_id)->orWhere('id_osis',$book_id)->orWhere('id_usfx',$book_id)->first();
	    if(isset($book)) $book_id = $book->id;
		$fileset = BibleFileset::join('bible_fileset_connections', 'bible_fileset_connections.hash_id', '=', 'bible_filesets.hash_id')
		->where('id',$bible_id)->when($bucket_id, function ($query) use ($bucket_id) {
			return $query->where('bucket_id', $bucket_id);
		})->where('set_type_code',$type)->first();
	    if(!$fileset) return $this->setStatusCode(404)->replyWithError("No Fileset Found in the `".$bucket_id."` Bucket for the provided params");
		$bible_id = ($fileset->bible_id) ? $fileset->bible_id."/" : "";

		switch($fileset->set_type_code) {
			case "audio_drama":
			case "audio":        {$fileset_type = "audio"; break; }
			case "text_plain":
			case "text_format":  {$fileset_type = "text"; break; }
			case "video":        {$fileset_type = "video"; break; }
			case "app":          {$fileset_type = "app"; break; }
			default:             {$fileset_type = "text"; break;}
		}

	    $fileSetChapters = BibleFile::with('book','bible.books')
		    ->join('books', 'books.id', '=', 'bible_files.book_id')
			->where('hash_id',$fileset->hash_id)
			->when($chapter_id, function ($query) use ($chapter_id) {
			    return $query->where('chapter_start', $chapter_id);
			})->when($book_id, function ($query) use ($book_id) {
			    return $query->where('book_id', $book_id);
		    })->orderBy('books.book_order')->orderBy('chapter_start')->get();

	    if(!$fileset) return $this->setStatusCode(404)->replyWithError("No Fileset Chapters Found for the provided params");

	    foreach ($fileSetChapters as $key => $fileSet_chapter) {
			$fileSetChapters[$key]->file_name = Bucket::signedUrl($fileset_type.'/'.$bible_id.$fileset->id.'/'.$fileSet_chapter->file_name, $bucket_id, $lifespan);
	    }
	    return $this->reply(fractal()->collection($fileSetChapters)->serializeWith($this->serializer)->transformWith(new FileSetTransformer()));
    }

	/**
	 *
	 * @OAS\Get(
	 *     path="/bibles/filesets/{id}/download",
	 *     tags={"Version 4"},
	 *     summary="Download a Fileset",
	 *     description="Returns a an entire fileset or a selected portion of a fileset for download",
	 *     operationId="v4_bible_filesets.download",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="The fileset ID",
	 *         required=true,
	 *         @OAS\Schema(ref="#/components/schemas/BibleFileSet/properties/id")
	 *     ),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="The requested fileset as a zipped download",
	 *         @OAS\MediaType(mediaType="application/zip")
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
    public function download($id)
    {
	    $set_id = CheckParam('fileset_id',$id);
	    $bucket_id = checkParam('bucket_id', null);
	    $books = CheckParam('book_ids',null,'optional');

	    $fileset = BibleFileset::where('id',$set_id)->where('bucket_id',$bucket_id)->first();
	    if(!$fileset) return $this->replyWithError("Fileset ID not found");

	    // Filter Download By Books
	    if($books) {
	    	$books = explode(',',$books);
		    $files = BibleFile::with('book')->where('hash_id',$fileset->hash_id)->whereIn('book_id',$books)->get();
		    $books = $files->map(function ($file) {
		    	$testamentLetter = ($file->book->book_testament == "NT") ? "B" : "A";
			    return $testamentLetter.str_pad($file->book->testament_order, 2, 0, STR_PAD_LEFT);
		    })->unique();
	    }
	    Bucket::download($files,'s3_fcbh', 'dbp_dev', 5, $books);
    }

	/**
	 *
	 * @OAS\Get(
	 *     path="/bibles/filesets/{id}/podcast",
	 *     tags={"Version 4"},
	 *     summary="Audio Filesets as Podcasts",
	 *     description="An audio Fileset in an RSS format suitable for consumption by iTunes",
	 *     operationId="v4_bible_filesets.podcast",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="The fileset ID",
	 *         required=true,
	 *         @OAS\Schema(ref="#/components/schemas/BibleFileSet/properties/id")
	 *     ),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="The requested fileset as a rss compatible xml podcast",
	 *         @OAS\MediaType(mediaType="application/xml")
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function podcast($id)
	{
		$bucket_id = checkParam('bucket', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$fileset = BibleFileset::with('files.currentTitle','bible')->where('id',$id)->where('bucket_id',$bucket_id)->first();
		if(!$fileset) return $this->replyWithError("No Fileset exists for this ID");

		$rootElementName = 'rss';
		$rootAttributes  = ['xmlns:itunes' => "http://www.itunes.com/dtds/podcast-1.0.dtd",'xmlns:atom' => "http://www.w3.org/2005/Atom",'xmlns:media' => "http://search.yahoo.com/mrss/",'version' => "2.0"];
		$podcast = fractal()->item($fileset)->serializeWith($this->serializer)->transformWith(new FileSetTransformer())->ToArray();
		return $this->reply($podcast, ['rootElementName' => $rootElementName, 'rootAttributes' => $rootAttributes]);
	}

	/**
	 *
	 * Copyright
	 *
	 * @OAS\Get(
	 *     path="/bibles/filesets/{id}/copyright",
	 *     tags={"Version 4"},
	 *     summary="Fileset Copyright information",
	 *     description="A fileset's copyright information and organizational connections",
	 *     operationId="v4_bible_filesets.copyright",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(
	 *         name="iso",
	 *         in="path",
	 *         description="The language that the organization's name should be translated in",
	 *         required=true,
	 *         @OAS\Schema(ref="#/components/schemas/Language/properties/iso")
	 *     ),
	 *     @OAS\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="The fileset ID",
	 *         required=true,
	 *         @OAS\Schema(ref="#/components/schemas/BibleFileSet/properties/id")
	 *     ),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="The requested fileset copyright",
	 *         @OAS\MediaType(mediaType="application/json")
	 *     )
	 * )
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function copyright($id)
	{
		$iso = checkParam('iso',null,'optional') ?? "eng";
		$fileset = BibleFileset::with(['copyright.organizations.logos','copyright.organizations.translations' => function ($query) use ($iso) {$query->where('language_iso', $iso);}])->find($id);
		return $this->reply($fileset);
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
	 * @OAS\POST(
	 *     path="/bibles/filesets/",
	 *     tags={"Version 4"},
	 *     summary="Create a brand new Fileset",
	 *     description="Create a new Bible Fileset",
	 *     operationId="v4_bible_filesets.store",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="The completed fileset",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_bible_filesets.show")
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
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
	 * Returns the Available Media Types for Filesets within the API.
	 *
	 * @OAS\GET(
	 *     path="/bibles/filesets/media/types",
	 *     tags={"Version 4"},
	 *     summary="Available fileset types",
	 *     description="A list of all the file types that exist within the filesets",
	 *     operationId="v4_bible_filesets.types",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="The fileset types",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(type="object",example={"audio_drama"="Dramatized Audio","audio"="Audio","text_plain"="Plain Text","text_format"="Formatted Text","video"="Video","app"="Application"})
	 *         ),
	 *         @OAS\MediaType(
	 *            mediaType="application/xml",
	 *            @OAS\Schema(type="object",example={"audio_drama"="Dramatized Audio","audio"="Audio","text_plain"="Plain Text","text_format"="Formatted Text","video"="Video","app"="Application"})
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function mediaTypes() {
	    return $this->reply(BibleFilesetType::all()->pluck('name','set_type_code'));
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
	 *
	 * @OAS\PUT(
	 *     path="/bibles/filesets/{id}",
	 *     tags={"Version 4"},
	 *     summary="Available fileset",
	 *     description="A list of all the file types that exist within the filesets",
	 *     operationId="v4_bible_filesets.update",
	 *     @OAS\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="The fileset ID",
	 *         example="ENGESV",
	 *         required=true,
	 *         @OAS\Schema(ref="#/components/schemas/BibleFileSet/properties/id")
	 *     ),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="The fileset just edited",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/responses/v4_bible_filesets.show")
	 *         )
	 *     )
	 * )
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
