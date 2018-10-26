<?php

namespace App\Http\Controllers\Bible;

use App\Traits\AccessControlAPI;
use App\Traits\CallsBucketsTrait;
use Validator;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFilesetType;
use App\Models\Bible\Book;
use App\Models\Language\Language;
use App\Helpers\AWS\Bucket;
use App\Models\User\Key;

use App\Transformers\FileSetTransformer;

class BibleFileSetsController extends APIController
{

	use AccessControlAPI;
	use CallsBucketsTrait;

	/**
	 *
	 * @OA\Get(
	 *     path="/bibles/filesets/{id}",
	 *     tags={"Bibles"},
	 *     summary="Returns Bibles Filesets",
	 *     description="Returns a list of bible filesets",
	 *     operationId="v4_bible_filesets.show",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", description="The fileset ID", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="versification", in="path", description="The versification system", @OA\Schema(ref="#/components/schemas/Bible/properties/versification")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/BibleFileset")
	 *         )
	 *     )
	 * )
	 *
	 * @param null $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id = null)
	{
		//if (!$this->api) return view('bibles.filesets.index');
		$fileset_id    = checkParam('dam_id|fileset_id', $id);
		$chapter_id    = checkParam('chapter_id', null, 'optional');
		$book_id       = checkParam('book_id', null, 'optional');
		$bucket_id     = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$lifespan      = checkParam('lifespan', null, 'optional') ?? 5;
		$type          = checkParam('type');
		$versification = checkParam('versification', null, 'optional');

		$book = $book_id ? Book::where('id', $book_id)->orWhere('id_osis', $book_id)->orWhere('id_usfx', $book_id)->first() : null;
		if($book !== null) $book_id = $book->id;
		$fileset = BibleFileset::with('bible')->where('id', $fileset_id)->when($bucket_id,
			function ($query) use ($bucket_id) {
				return $query->where('bucket_id', $bucket_id);
			})->where('set_type_code', $type)->first();
		if(!$fileset) return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404_bucket', ['bucket_id' => $bucket_id]));

		$access_control_type = (strpos($fileset->set_type_code, 'audio') !== false) ? 'download' : 'api';
		$access_control = $this->accessControl($this->key, $access_control_type);
		if(!\in_array($fileset->hash_id, $access_control->hashes, true)) return $this->setStatusCode(403)->replyWithError(trans('api.bible_fileset_errors_401'));

		$bible         = $fileset->bible->first() ?: false;
		$bible_path    = $bible->id ? $bible->id . '/' : '';
		$versification = (!$versification) ? $bible->versification : 'protestant';

		switch ($fileset->set_type_code) {
			case 'audio_drama':
			case 'audio':       { $fileset_type = 'audio'; break;}
			case 'text_plain':
			case 'text_format': { $fileset_type = 'text'; break;}
			case 'video_stream':
			case 'video': { $fileset_type = 'video'; break;}
			case 'app':   { $fileset_type = 'app'; break;}
			default:      { $fileset_type = 'text'; break;}
		}

		$fileSetChapters = BibleFile::where('hash_id',$fileset->hash_id)
			->join(env('DBP_DATABASE').'.bible_books', function($q) use($bible) {
				$q->on('bible_books.book_id', 'bible_files.book_id')->where('bible_id',$bible->id);
			})
			->join(env('DBP_DATABASE').'.books','books.id', 'bible_files.book_id')
			->when($chapter_id, function ($query) use ($chapter_id) {
				return $query->where('bible_files.chapter_start', $chapter_id);
			})->when($book_id, function ($query) use ($book_id) {
				return $query->where('bible_files.book_id', $book_id);
			})->select([
				'bible_files.duration',
				'bible_files.hash_id',
				'bible_files.id',
				'bible_files.book_id',
				'bible_files.chapter_start',
				'bible_files.chapter_end',
				'bible_files.verse_start',
				'bible_files.verse_end',
				'bible_files.file_name',
				'bible_books.name as book_name',
				'books.' . $versification . '_order as book_order'
			])->get();
		if ($fileSetChapters->count() === 0) return $this->setStatusCode(404)->replyWithError('No Fileset Chapters Found for the provided params');

		if($fileset->set_type_code != 'video_stream') {
			$transaction_id = random_int(0,10000000);
			foreach ($fileSetChapters as $key => $fileSet_chapter) {
				$fileSetChapters[$key]->file_name = $this->signedUrl($fileset_type . '/' . $bible_path . $fileset->id . '/' . $fileSet_chapter->file_name, $bucket_id, $transaction_id);
			}
		} else {
			foreach ($fileSetChapters as $key => $fileSet_chapter) {
				$fileSetChapters[$key]->file_name = route('v4_video_stream', ['fileset_id' => $fileset->id,'file_id' => $fileSet_chapter->id]);
			}
		}

		return $this->reply(fractal($fileSetChapters, new FileSetTransformer(),$this->serializer), [], $transaction_id ?? '');
	}

	/**
	 *
	 * @OA\Get(
	 *     path="/bibles/filesets/{id}/download",
	 *     tags={"Bibles"},
	 *     summary="Download a Fileset",
	 *     description="Returns a an entire fileset or a selected portion of a fileset for download",
	 *     operationId="v4_bible_filesets.download",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The requested fileset as a zipped download",
	 *         @OA\MediaType(mediaType="application/zip")
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function download($id)
	{
		$set_id    = CheckParam('fileset_id', $id);
		$bucket_id = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$books     = CheckParam('book_ids', null, 'optional');

		$fileset = BibleFileset::where('id', $set_id)->where('bucket_id', $bucket_id)->first();
		if (!$fileset) {
			return $this->replyWithError("Fileset ID not found");
		}

		// Filter Download By Books
		if ($books) {
			$books = explode(',', $books);
			$files = BibleFile::with('book')->where('hash_id', $fileset->hash_id)->whereIn('book_id', $books)->get();
			$books = $files->map(function ($file) {
				$testamentLetter = ($file->book->book_testament == "NT") ? "B" : "A";
				return $testamentLetter . str_pad($file->book->testament_order, 2, 0, STR_PAD_LEFT);
			})->unique();
		}
		Bucket::download($files, 's3_fcbh', 'dbp.test', 5, $books);
	}

	/**
	 *
	 * @OA\Get(
	 *     path="/bibles/filesets/{id}/podcast",
	 *     tags={"Bibles"},
	 *     summary="Audio Filesets as Podcasts",
	 *     description="An audio Fileset in an RSS format suitable for consumption by iTunes",
	 *     operationId="v4_bible_filesets.podcast",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The requested fileset as a rss compatible xml podcast",
	 *         @OA\MediaType(mediaType="application/xml")
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function podcast($id)
	{
		$bucket_id = checkParam('bucket|bucket_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');
		$fileset   = BibleFileset::with('files.currentTitle', 'bible.books')->where('id', $id)->where('bucket_id', $bucket_id)->first();
		if (!$fileset) return $this->replyWithError("No Fileset exists for this ID");

		$rootElementName = 'rss';
		$rootAttributes  = ['xmlns:itunes' => "http://www.itunes.com/dtds/podcast-1.0.dtd", 'xmlns:atom' => "http://www.w3.org/2005/Atom", 'xmlns:media' => "http://search.yahoo.com/mrss/", 'version' => "2.0"];
		$podcast         = fractal($fileset,new FileSetTransformer())->serializeWith($this->serializer);
		return $this->reply($podcast, ['rootElementName' => $rootElementName, 'rootAttributes' => $rootAttributes]);
	}

	/**
	 *
	 * Copyright
	 *
	 * @OA\Get(
	 *     path="/bibles/filesets/{id}/copyright",
	 *     tags={"Bibles"},
	 *     summary="Fileset Copyright information",
	 *     description="A fileset's copyright information and organizational connections",
	 *     operationId="v4_bible_filesets.copyright",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="bucket_id", in="path", required=true, description="The bucket id", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/bucket_id")),
	 *     @OA\Parameter(name="type", in="path", required=true, description="The set type code", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/set_type_code")),
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="query",
	 *         description="The fileset ID",
	 *         required=true,
	 *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The requested fileset copyright",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleFileset"))
	 *     )
	 * )
	 *
	 * @param string $id
	 *
	 * @return mixed
	 */
	public function copyright($id)
	{
		$iso = checkParam('iso', null, 'optional') ?? 'eng';
		$type = checkParam('type', null, 'optional');
		$bucket_id = checkParam('bucket|bucket_id', null, 'optional') ?? 'dbp.test';

		$language = Language::where('iso',$iso)->first();
		$fileset = BibleFileset::with(['copyright.organizations.logos', 'copyright.organizations.translations' => function ($q) use ($language) {
			$q->where('language_id', $language->id);
		}])
		->when($bucket_id, function ($q) use($bucket_id) {
			$q->where('bucket_id', $bucket_id);
		})
		->when($type, function ($q) use($type) {
			$q->where('set_type_code', $type);
		})->select(['hash_id','id','bucket_id','set_type_code as type','set_size_code as size'])->where('id',$id)->first();

		return $this->reply($fileset);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$bibles = Bible::with('currentTranslation')->select('id')->get()->pluck('currentTranslation.name', 'id');
		return view('bibles.filesets.create', compact('bibles'));
	}

	/**
	 *
	 * @OA\Post(
	 *     path="/bibles/filesets/",
	 *     tags={"Bibles"},
	 *     summary="Create a brand new Fileset",
	 *     description="Create a new Bible Fileset",
	 *     operationId="v4_bible_filesets.store",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Fields for Bible Fileset Creation",
	 *          @OA\MediaType(mediaType="application/json",                  @OA\Schema(ref="#/components/schemas/BibleFileset")),
	 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded", @OA\Schema(ref="#/components/schemas/BibleFileset"))
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The completed fileset",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/BibleFileset")
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function store(Request $request)
	{
		$this->validateUser(Auth::user());
		$this->validateBibleFileset();

		$fileset = BibleFileset::create(request()->all());

		// $bible = request()->file('file');

		// ProcessBible::dispatch($request->file('zip'), $fileset->id);
		return view('bibles.filesets.thanks', compact('fileset'));
	}

	/**
	 * Returns the Available Media Types for Filesets within the API.
	 *
	 * @OA\GET(
	 *     path="/bibles/filesets/media/types",
	 *     tags={"Bibles"},
	 *     summary="Available fileset types",
	 *     description="A list of all the file types that exist within the filesets",
	 *     operationId="v4_bible_filesets.types",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The fileset types",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(type="object",example={"audio_drama"="Dramatized Audio","audio"="Audio","text_plain"="Plain Text","text_format"="Formatted Text","video"="Video","app"="Application"})
	 *         ),
	 *         @OA\MediaType(
	 *            mediaType="application/xml",
	 *            @OA\Schema(type="object",example={"audio_drama"="Dramatized Audio","audio"="Audio","text_plain"="Plain Text","text_format"="Formatted Text","video"="Video","app"="Application"})
	 *         )
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 *
	 */
	public function mediaTypes()
	{
		return $this->reply(BibleFilesetType::all()->pluck('name', 'set_type_code'));
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
	 *
	 * @OA\PUT(
	 *     path="/bibles/filesets/{fileset_id}",
	 *     tags={"Bibles"},
	 *     summary="Available fileset",
	 *     description="A list of all the file types that exist within the filesets",
	 *     operationId="v4_bible_filesets.update",
	 *     @OA\Parameter(
	 *          name="fileset_id",
	 *          in="path",
	 *          required=true,
	 *          description="The fileset ID",
	 *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
	 *     ),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The fileset just edited",
	 *         @OA\MediaType(
	 *            mediaType="application/json",
	 *            @OA\Schema(ref="#/components/schemas/BibleFileset")
	 *         )
	 *     )
	 * )
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function update($id, Request $request)
	{
		$this->validateUser(Auth::user());
		$this->validateBibleFileset($request);

		$fileset = BibleFileset::find($id);
		$fileset->fill($request->all())->save();

		if ($this->api) {
			return $this->setStatusCode(201)->reply($fileset);
		}
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
		$this->validateUser(Auth::user());

		$fileset = BibleFileset::find($id);
		$fileset->delete();

		if ($this->api) {
			return $this->setStatusCode(200)->reply($fileset);
		}
		return view('bibles.filesets.thanks', compact('fileset'));
	}

	/**
	 * Ensure the current User has permissions to alter the alphabets
	 *
	 * @return \App\Models\User\User|mixed|null
	 */
	private function validateUser($fileset = null)
	{
		$user = Auth::user();
		if (!$user) {
			$key = Key::where('key', $this->key)->first();
			if (!isset($key)) {
				return $this->setStatusCode(403)->replyWithError('No Authentication Provided or invalid Key');
			}
			$user = $key->user;
		}
		if (!$user->archivist AND !$user->admin) {
			if ($fileset) {
				$userIsAMember = $user->organizations->where('organization_id', $fileset->organization->id)->first();
				if ($userIsAMember) {
					return $user;
				}
			}
			return $this->setStatusCode(401)->replyWithError("You don't have permission to edit this filesets");
		}
		return $user;
	}

	/**
	 * Ensure the current alphabet change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateBibleFileset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id'            => ($request->method() == "POST") ? 'required|unique:bible_filesets,id|max:16|min:6' : 'required|exists:bible_filesets,id|max:16|min:6',
			'bucket_id'     => 'string|maxLength:64',
			'set_type_code' => 'string|maxLength:16',
			'set_size_code' => 'string|maxLength:9',
			'hidden'        => 'boolean',
		]);

		if ($validator->fails()) {
			if ($this->api) {
				return $this->setStatusCode(422)->replyWithError($validator->errors());
			}
			if (!$this->api) {
				return redirect('dashboard/bible-filesets/create')->withErrors($validator)->withInput();
			}
		}

	}

	private function notifyArchivist()
	{

	}

}
