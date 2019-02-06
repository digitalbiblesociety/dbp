<?php

namespace App\Http\Controllers\Bible;

use App\Models\Organization\Asset;
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
use App\Models\User\Key;

use App\Transformers\FileSetTransformer;

class BibleFileSetsController extends APIController
{
    use AccessControlAPI;
    use CallsBucketsTrait;

    /**
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}",
     *     tags={"Bibles"},
     *     summary="Returns Bibles Filesets",
     *     description="Returns a list of bible filesets",
     *     operationId="v4_bible_filesets.show",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="fileset_id", in="path", description="The fileset ID", required=true,
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(name="book_id", in="query", description="Will filter the results by the given book",
     *          @OA\Schema(ref="#/components/schemas/Book/properties/id")
     *     ),
     *     @OA\Parameter(name="chapter_id", in="query", description="Will filter the results by the given chapter",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")
     *     ),
     *     @OA\Parameter(name="asset_id", in="query", description="Will filter the results by the given Asset",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id")
     *     ),
     *     @OA\Parameter(name="versification", in="query", description="The versification system",
     *          @OA\Schema(ref="#/components/schemas/Bible/properties/versification")
     *     ),
     *     @OA\Parameter(name="type", in="query", description="The fileset type",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/set_type_code")
     *     ),
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
     * @throws \Exception
     */
    public function show($id = null)
    {
        //if (!$this->api) return view('bibles.filesets.index');
        $fileset_id    = checkParam('dam_id|fileset_id', true, $id);
        $book_id       = checkParam('book_id');
        $chapter_id    = checkParam('chapter_id');
        $asset_id      = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $versification = checkParam('versification');
        $type          = checkParam('type', true);

        $book = $book_id ? Book::where('id', $book_id)->orWhere('id_osis', $book_id)->orWhere('id_usfx', $book_id)->first() : null;
        if ($book !== null) {
            $book_id = $book->id;
        }
        $fileset = BibleFileset::with('bible')->where('id', $fileset_id)->when($asset_id, function ($query) use ($asset_id) {
                return $query->where('asset_id', $asset_id);
            })->where('set_type_code', $type)->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404_asset', ['asset_id' => $asset_id]));
        }

        $access_control = $this->accessControl($this->key);
        if (!\in_array($fileset->hash_id, $access_control->hashes, true)) {
            return $this->setStatusCode(403)->replyWithError(trans('api.bible_fileset_errors_401'));
        }

        $bible         = $fileset->bible->first() ?: false;
        $bible_path    = $bible ? $bible->id . '/' : '';
        $versification = (!$versification) ? $bible->versification : 'protestant';

        switch ($fileset->set_type_code) {
            case 'audio_drama':
            case 'audio':
                $fileset_type = 'audio';
                break;
            case 'text_plain':
            case 'text_format':
                $fileset_type = 'text';
                break;
            case 'video_stream':
            case 'video':
                $fileset_type = 'video';
                break;
            case 'app':
                $fileset_type = 'app';
                break;
            default:
                $fileset_type = 'text';
                break;
        }

        $fileSetChapters = BibleFile::where('hash_id', $fileset->hash_id)
            ->join(config('database.connections.dbp.database').'.bible_books', function ($q) use ($bible) {
                $q->on('bible_books.book_id', 'bible_files.book_id')->where('bible_id', $bible->id);
            })
            ->join(config('database.connections.dbp.database').'.books', 'books.id', 'bible_files.book_id')
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
        if ($fileSetChapters->count() === 0) {
            return $this->setStatusCode(404)->replyWithError('No Fileset Chapters Found for the provided params');
        }

        if ($fileset->set_type_code !== 'video_stream') {
            $transaction_id = random_int(0, 10000000);
            foreach ($fileSetChapters as $key => $fileSet_chapter) {
                $fileSetChapters[$key]->file_name = $this->signedUrl($fileset_type . '/' . $bible_path . $fileset->id . '/' . $fileSet_chapter->file_name, $asset_id, $transaction_id);
            }
        } else {
            foreach ($fileSetChapters as $key => $fileSet_chapter) {
                $fileSetChapters[$key]->file_name = route('v4_video_stream', ['fileset_id' => $fileset->id,'file_id' => $fileSet_chapter->id]);
            }
        }

        return $this->reply(fractal($fileSetChapters, new FileSetTransformer(), $this->serializer), [], $transaction_id ?? '');
    }

    /**
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/download",
     *     tags={"Bibles"},
     *     summary="Download a Fileset",
     *     description="Returns a an entire fileset or a selected portion of a fileset for download",
     *     operationId="v4_bible_filesets.download",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="fileset_id", in="path", required=true, description="The fileset ID",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(name="asset_id", in="path", required=true, description="The fileset ID",
     *          @OA\Schema(ref="#/components/schemas/Asset/properties/id")
     *     ),
     *     @OA\Parameter(name="book_ids", in="path", required=true,
     *          description="The list of book ids to download content for seperated by commas",
     *          example="GEN,EXO,MAT,REV",
     *          @OA\Schema(ref="#/components/schemas/Book/properties/id")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The requested fileset as a zipped download",
     *         @OA\MediaType(mediaType="application/zip")
     *     )
     * )
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function download($id)
    {
        $set_id    = checkParam('fileset_id', true, $id);
        $asset_id  = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $books     = checkParam('book_ids');
        $files     = null;

        $fileset = BibleFileset::where('id', $set_id)->where('asset_id', $asset_id)->first();
        if (!$fileset) {
            return $this->replyWithError('Fileset ID not found');
        }

        // Filter Download By Books
        if ($books) {
            $books = explode(',', $books);
            $files = BibleFile::with('book')->where('hash_id', $fileset->hash_id)->whereIn('book_id', $books)->get();
            if (!$files) {
                return $this->setStatusCode(404)->replyWithError('Files not found');
            }
            $books = $files->map(function ($file) {
                $testamentLetter = ($file->book->book_testament === 'NT') ? 'B' : 'A';
                return $testamentLetter . str_pad($file->book->testament_order, 2, 0, STR_PAD_LEFT);
            })->unique();
        }

        Asset::download($files, 's3_fcbh', 'dbp.test', 5, $books);
        return $this->reply('download successful');
    }

    /**
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/podcast",
     *     tags={"Bibles"},
     *     summary="Audio Filesets as Podcasts",
     *     description="An audio Fileset in an RSS format suitable for consumption by iTunes",
     *     operationId="v4_bible_filesets.podcast",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="fileset_id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="The requested fileset as a rss compatible xml podcast",
     *         @OA\MediaType(mediaType="application/xml")
     *     )
     * )
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function podcast($id)
    {
        $asset_id = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $fileset   = BibleFileset::with('translations', 'files.currentTitle', 'bible.books')->where('id', $id)->where('asset_id', $asset_id)->first();
        if (!$fileset) {
            return $this->replyWithError(trans('api.bible_fileset_errors_404'));
        }

        $rootElementName = 'rss';
        $rootAttributes  = [
            'xmlns:itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
            'xmlns:atom' => 'http://www.w3.org/2005/Atom',
            'xmlns:media' => 'http://search.yahoo.com/mrss/',
            'version' => '2.0'
        ];
        $podcast         = fractal($fileset, new FileSetTransformer(), $this->serializer);
        return $this->reply($podcast, ['rootElementName' => $rootElementName, 'rootAttributes' => $rootAttributes]);
    }

    /**
     *
     * Copyright
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/copyright",
     *     tags={"Bibles"},
     *     summary="Fileset Copyright information",
     *     description="A fileset's copyright information and organizational connections",
     *     operationId="v4_bible_filesets.copyright",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(
     *          name="fileset_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id"),
     *          description="The fileset ID to retrieve the copyright information for"
     *     ),
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id"),
     *          description="The asset id which contains the Fileset"
     *     ),
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/set_type_code"),
     *          description="The set type code for the fileset"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id"),
     *         description="The fileset ID",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The requested fileset copyright",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleFileset"))
     *     )
     * )
     *
     * @see https://api.dbp.test/bibles/filesets/ENGESV/copyright?key=API_KEY&v=4&type=text_plain&pretty
     * @param string $id
     * @return mixed
     */
    public function copyright($id)
    {
        $iso = checkParam('iso') ?? 'eng';
        $type = checkParam('type', true);
        $asset_id = checkParam('bucket|bucket_id|asset_id') ?? 'dbp-prod';

        $cache_string = strtolower('bible_fileset_copyright:'.$asset_id.':'.$id.':'.$type.$iso);
        $fileset = \Cache::remember($cache_string, now()->addDay(), function () use ($iso, $type, $asset_id, $id) {
            $language_id = optional(Language::where('iso', $iso)->select('id')->first())->id;
            return BibleFileset::where('id', $id)->with([
                'copyright.organizations.logos',
                'copyright.organizations.translations' => function ($q) use ($language_id) {
                    $q->where('language_id', $language_id);
                }])
                ->when($asset_id, function ($q) use ($asset_id) {
                    $q->where('asset_id', $asset_id);
                })
                ->when($type, function ($q) use ($type) {
                    $q->where('set_type_code', $type);
                })->select(['hash_id','id','asset_id','set_type_code as type','set_size_code as size'])->first();
        });

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
     */
    public function store()
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
     * @OA\Put(
     *     path="/bibles/filesets/{fileset_id}",
     *     tags={"Bibles"},
     *     summary="Available fileset",
     *     description="A list of all the file types that exist within the filesets",
     *     operationId="v4_bible_filesets.update",
     *     @OA\Parameter(name="fileset_id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
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
    public function update($id)
    {
        $this->validateUser(Auth::user());
        $this->validateBibleFileset(request());

        $fileset = BibleFileset::find($id);
        $fileset->fill(request()->all())->save();

        if ($this->api) {
            return $this->setStatusCode(201)->reply($fileset);
        }
        return view('bibles.filesets.thanks', compact('fileset'));
    }

    /**
     * Ensure the current User has permissions to alter the alphabets
     *
     * @param null $fileset
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
        if (!$user->archivist && !$user->admin) {
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
     * Ensure the current fileset change is valid
     *
     * @return mixed
     */
    private function validateBibleFileset()
    {
        $validator = Validator::make(request()->all(), [
            'id'            => (request()->method() === 'POST') ? 'required|unique:bible_filesets,id|max:16|min:6' : 'required|exists:bible_filesets,id|max:16|min:6',
            'asset_id'     => 'string|maxLength:64',
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
        return null;
    }
}
