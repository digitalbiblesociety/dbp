<?php

namespace App\Http\Controllers\Bible;

use Illuminate\Support\Str;
use App\Models\Organization\Asset;
use App\Traits\AccessControlAPI;
use App\Traits\CallsBucketsTrait;
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
     *     @OA\Parameter(name="fileset_id", in="path", description="The fileset ID", required=true,
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(name="book_id", in="query", description="Will filter the results by the given book. For a complete list see the `book_id` field in the `/bibles/books` route.",
     *          @OA\Schema(ref="#/components/schemas/Book/properties/id")
     *     ),
     *     @OA\Parameter(name="chapter_id", in="query", description="Will filter the results by the given chapter",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")
     *     ),
     *     @OA\Parameter(name="asset_id", in="query", description="Will filter the results by the given Asset",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id")
     *     ),
     *     @OA\Parameter(name="type", in="query", description="The fileset type", required=true,
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
        $fileset_id    = checkParam('dam_id|fileset_id', true, $id);
        $book_id       = checkParam('book_id');
        $chapter_id    = checkParam('chapter_id');
        $asset_id      = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $type          = checkParam('type', true);

        $cache_string = 'bible_filesets_show:'.$this->v.':'.$fileset_id.$book_id.$type.$chapter_id.$asset_id;
        $fileset_chapters = \Cache::remember($cache_string, now()->addMinutes(20), function () use ($fileset_id, $book_id, $type, $chapter_id, $asset_id) {
            $book = Book::where('id', $book_id)->orWhere('id_osis', $book_id)->orWhere('id_usfx', $book_id)->first();
            $fileset = BibleFileset::with('bible')->uniqueFileset($fileset_id, $asset_id, $type)->first();
            if (!$fileset) {
                return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404'));
            }

            $access_blocked = $this->blockedByAccessControl($fileset);
            if ($access_blocked) {
                return $access_blocked;
            }

            $bible = optional($fileset->bible)->first();
            $fileset_chapters = BibleFile::where('hash_id', $fileset->hash_id)
                ->leftJoin(config('database.connections.dbp.database').'.bible_books', function ($q) use ($bible) {
                    $q->on('bible_books.book_id', 'bible_files.book_id')->where('bible_books.bible_id', $bible->id);
                })
                ->leftJoin(config('database.connections.dbp.database').'.books', 'books.id', 'bible_files.book_id')
                ->when($chapter_id, function ($query) use ($chapter_id) {
                    return $query->where('bible_files.chapter_start', $chapter_id);
                })->when($book, function ($query) use ($book) {
                    return $query->where('bible_files.book_id', $book->id);
                })
                  ->select([
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
                    'books.protestant_order as book_order'
                ])->get();

            if ($fileset_chapters->count() === 0) {
                return $this->setStatusCode(404)->replyWithError('No Fileset Chapters Found for the provided params');
            }

            return fractal($this->generateFilesetChapters($fileset, $fileset_chapters, $bible, $asset_id), new FileSetTransformer(), $this->serializer);
        });


        return $this->reply($fileset_chapters, [], $transaction_id ?? '');
    }

    private function signedPath($bible, $fileset, $fileset_chapter)
    {
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

        return $fileset_type . '/' . ($bible ? $bible->id . '/' : '') . $fileset->id . '/' . $fileset_chapter->file_name;
    }

    /**
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/download",
     *     tags={"Bibles"},
     *     summary="Download a Fileset",
     *     description="Returns a an entire fileset or a selected portion of a fileset for download",
     *     operationId="v4_bible_filesets.download",
     *     @OA\Parameter(name="fileset_id", in="path", required=true, description="The fileset ID",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(name="asset_id", in="query", required=true, description="The fileset ID",
     *          @OA\Schema(ref="#/components/schemas/Asset/properties/id")
     *     ),
     *     @OA\Parameter(name="fileset_type", in="query", description="The type of fileset being queried",
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/set_type_code")
     *     ),
     *     @OA\Parameter(name="book_ids", in="query", required=true,
     *          description="The list of book ids to download content for separated by commas. For a complete list see the `book_id` field in the `/bibles/books` route.",
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
        $id        = checkParam('fileset_id', true, $id);
        $asset_id  = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $type      = checkParam('fileset_type');
        $books     = checkParam('book_ids');
        $files     = null;

        $fileset = BibleFileset::uniqueFileset($id, $asset_id, $type)->first();
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
     * Copyright
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/copyright",
     *     tags={"Bibles"},
     *     summary="Fileset Copyright information",
     *     description="A fileset's copyright information and organizational connections",
     *     operationId="v4_bible_filesets.copyright",
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
     *          name="iso",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Language/properties/iso", default="eng"),
     *          description="The iso code to filter organization translations by. For a complete list see the `iso` field in the `/languages` route."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The requested fileset copyright",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="text/-yaml", @OA\Schema(ref="#/components/schemas/BibleFileset"))
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
     * Returns the Available Media Types for Filesets within the API.
     *
     * @OA\GET(
     *     path="/bibles/filesets/media/types",
     *     tags={"Bibles"},
     *     summary="Available fileset types",
     *     description="A list of all the file types that exist within the filesets",
     *     operationId="v4_bible_filesets.types",
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
     *         ),
     *         @OA\MediaType(
     *            mediaType="text/x-yaml",
     *            @OA\Schema(type="object",example={"audio_drama"="Dramatized Audio","audio"="Audio","text_plain"="Plain Text","text_format"="Formatted Text","video"="Video","app"="Application"})
     *         ),
     *         @OA\MediaType(
     *            mediaType="text/csv",
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
     * @param      $fileset
     * @param      $fileset_chapters
     * @param      $bible
     * @param      $asset_id
     *
     * @throws \Exception
     * @return array
     */
    private function generateFilesetChapters($fileset, $fileset_chapters, $bible, $asset_id)
    {
        $is_stream = $fileset->set_type_code === 'video_stream' || $fileset->set_type_code === 'audio_stream';
        $is_video = Str::contains($fileset->set_type_code, 'video');

        if ($is_stream) {
            foreach ($fileset_chapters as $key => $fileSet_chapter) {
                $fileset_chapters[$key]->file_name = route('v4_media_stream', ['fileset_id' => $fileset->id, 'file_id' => $fileSet_chapter->id]);
            }
        }

        if (!$is_stream) {
            foreach ($fileset_chapters as $key => $fileset_chapter) {
                $fileset_chapters[$key]->file_name = $this->signedUrl($this->signedPath($bible, $fileset, $fileset_chapter), $asset_id, random_int(0, 10000000));
            }
        }

        
        if ($is_video) {
            foreach ($fileset_chapters as $key => $fileset_chapter) {
                $fileset_chapters[$key]->thumbnail = $this->signedUrl('video/thumbnails/' . $fileset_chapters[$key]->book_id . '_' . str_pad($fileset_chapter->chapter_start, 2, '0', STR_PAD_LEFT) . '.jpg', $asset_id, random_int(0, 10000000));
            }
        }

        return $fileset_chapters;
    }
}
