<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;

use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFileTimestamp;

use App\Traits\CallsBucketsTrait;
use App\Transformers\AudioTransformer;
use Illuminate\Http\JsonResponse;

class AudioController extends APIController
{
    use CallsBucketsTrait;

    /**
     *
     * Returns an array of signed audio urls
     *
     * @version  4
     * @category v2_audio_path
     * @link     http://api.dbp4.org/audio/path - V4 Access
     * @link     https://api.dbp.test/audio/path?key=1234&v=4&pretty - V4 Test Access
     * @link     https://dbp.test/eng/docs/swagger/gen#/Version_2/v4_alphabets.one - V4 Test Docs
     *
     * @OA\Get(
     *     path="/audio/path",
     *     tags={"Library Audio"},
     *     summary="Returns Audio File path information",
     *     description="This call returns the file path information for audio files within a volume
               This information can be used with the response of the /audio/location call to create
               a URI to retrieve the audio files.",
     *     operationId="v2_audio_path",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="dam_id",
     *         in="path",
     *         description="The DAM ID for which to retrieve file path info.",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(name="chapter_id",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start"),
     *         description="If this value is the return will be limited to the provided chapter",
     *     ),
     *     @OA\Parameter(
     *         name="encoding",
     *         in="query",
     *         @OA\Schema(type="string",title="encoding",deprecated=true),
     *         description="The audio encoding format desired (No longer in use as Audio Files default to mp3)."
     *     ),
     *     @OA\Parameter(
     *         name="asset_id",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/Asset/properties/id"),
     *         description="The asset id that contains the resource."
     *     ),
     *     @OA\Parameter(name="book_id",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/Book/properties/id"),
     *         description="The USFM 2.4 book ID."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_audio_path"))
     *     )
     * )
     *
     * @see https://api.dbp.test/audio/path?key=1234&v=2&dam_id=ABIWBTN1DA&book_id=LUK
     * @return mixed
     * @throws \Exception
     */
    public function index()
    {
        // Check Params
        $fileset_id = checkParam('dam_id', true);
        $book_id    = checkParam('book_id');
        $chapter_id = checkParam('chapter_id');
        $asset_id   = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');

        $cache_string = strtolower("audio_index:".$asset_id.':'.$fileset_id.':'.$book_id.':'.$chapter_id);

        $audioChapters = \Cache::remember($cache_string, now()->addDay(), function () use ($fileset_id, $book_id, $chapter_id, $asset_id) {
            // Account for various book ids

            $book_id = optional(Book::where('id_osis', $book_id)->first())->id;

            // Fetch the Fileset
            $hash_id = optional(BibleFileset::uniqueFileset($fileset_id, $asset_id, 'audio', true)->select('hash_id')->first())->hash_id;
            if (!$hash_id) {
                return $this->setStatusCode(404)->replyWithError('No Audio Fileset could be found for: ' . $hash_id);
            }

            // Fetch The files
            $response = BibleFile::with('book', 'bible')->where('hash_id', $hash_id)
                ->when($chapter_id, function ($query) use ($chapter_id) {
                    return $query->where('chapter_start', $chapter_id);
                })->when($book_id, function ($query) use ($book_id) {
                    return $query->where('book_id', $book_id);
                })->orderBy('file_name');

            return $response->get();
        });

        // Transaction id to be passed to signedUrl
        $transaction_id = random_int(0, 10000000);
        foreach ($audioChapters as $key => $audio_chapter) {
            $audioChapters[$key]->file_name = $this->signedUrl('audio/' . $audio_chapter->bible->first()->id . '/' . $fileset_id . '/' . $audio_chapter->file_name, $asset_id, $transaction_id);
        }

        return $this->reply(fractal($audioChapters, new AudioTransformer(), $this->serializer), [], $transaction_id);
    }

    /**
     * Available Timestamps
     *
     * @OA\Get(
     *     path="/timestamps",
     *     tags={"Bibles"},
     *     summary="Returns Bible Filesets which have Audio timestamps",
     *     description="This call returns a list of hashes that have timestamp metadata associated
               with them. This data could be used to search audio bibles for a specific term, make
               karaoke verse & audio readings, or to jump to a specific location in an audio file.",
     *     operationId="v4_timestamps",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(response=204, description="No timestamps are available at this time"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible_timestamps")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible_timestamps")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible_timestamps")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_bible_timestamps"))
     *     )
     * )
     *
     *
     * @OA\Schema (
     *   type="array",
     *   schema="v4_bible_timestamps",
     *   description="The bibles hash returned for timestamps",
     *   title="Bible Timestamps",
     *   @OA\Xml(name="v4_bible.timestamps"),
     *   @OA\Items(
     *       @OA\Property(property="hash_id", ref="#/components/schemas/BibleFileset/properties/hash_id"),
     *     )
     *   )
     * )
     *
     *
     *
     * @return mixed
     */
    public function availableTimestamps()
    {
        $cache_string = 'audio_timestamp_hashes';
        $hashes = \Cache::remember($cache_string, 4800, function () {
            return BibleFile::has('timestamps')->select('hash_id')->distinct()->get();
        });
        if ($hashes->count() === 0) {
            return $this->setStatusCode(204)->replyWithError('No timestamps are available at this time');
        }
        return $this->reply($hashes);
    }

    /**
     * Returns a List of timestamps for a given Scripture Reference
     *
     * @OA\Get(
     *     path="/audio/versestart",
     *     tags={"Library Audio"},
     *     summary="Returns Audio timestamps for a specific reference",
     *     description="This route will return timestamps restricted to specific book and chapter
               combination for a fileset. Note that the fileset id must be available via the path
               `/timestamps`. At first, only a few filesets may have timestamps metadata applied.",
     *     operationId="v2_audio_timestamps",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="fileset_id", in="query", description="The specific fileset to return references for", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="book", in="query", description="The Book ID for which to return timestamps", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="chapter", in="query", description="The chapter for which to return timestamps", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_audio_timestamps")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_audio_timestamps")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v2_audio_timestamps"))
     *     )
     * )
     *
     *
     * @return mixed
     */
    public function timestampsByReference()
    {
        // Check Params
        $id       = checkParam('fileset_id|dam_id');
        $asset_id = checkParam('asset_id');
        $book     = checkParam('book|osis_code');
        $chapter  = checkParam('chapter_id|chapter_number');

        $book = Book::selectByID($book)->first();

        // Fetch Fileset & Files
        $fileset = BibleFileset::uniqueFileset($id, $asset_id, 'audio', true)->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));
        }

        $bible_files = BibleFile::where('hash_id', $fileset->hash_id)->where('book_id', $book->id)->where('chapter_start', $chapter)->get();

        // Fetch Timestamps
        $audioTimestamps = BibleFileTimestamp::whereIn('bible_file_id', $bible_files->pluck('id'))->orderBy('verse_start')->get();

        // Return Response
        return $this->reply($audioTimestamps);
    }


    /**
     * Returns a List of timestamps for a given word
     *
     * @OA\Get(
     *     path="/timestamps/search",
     *     tags={"Bibles"},
     *     summary="Returns audio timestamps for a specific word",
     *     description="This route will search the text for a specific word or phrase and return a
               collection of timestamps associated with the verse references connected to the term",
     *     operationId="v4_timestamps.tag",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="audio_fileset_id", in="query", description="The specific audio fileset to return references for", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="audio_asset_id", in="query", description="The specific audio asset id to return references for", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id")),
     *     @OA\Parameter(name="text_fileset_id", in="query", description="The specific text fileset to return references for", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="text_asset_id", in="query", description="The specific text asset id to return references for", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id")),
     *     @OA\Parameter(name="book_id", in="query", description="The specific book id to return references for", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="query", in="query", required=true, description="The tag for which to return timestamps", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_timestamps_tag")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_timestamps_tag")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_timestamps_tag")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_timestamps_tag")),
     *     )
     * )
     *
     *
     * @return mixed
     */
    public function timestampsByTag()
    {
        // Check Params
        $audio_fileset_id = checkParam('audio_fileset_id');
        $audio_asset_id   = checkParam('audio_asset_id');
        $text_fileset_id  = checkParam('text_fileset_id');
        $text_asset_id   = checkParam('text_asset_id');
        $book_id          = checkParam('book_id');
        $query            = checkParam('query', true);

        // Fetch Fileset & Books
        $audio_fileset = BibleFileset::uniqueFileset($audio_fileset_id, $audio_asset_id, 'audio', true)->first();
        if (!$audio_fileset) {
            return $this->setStatusCode(404)->replyWithError('Audio Fileset not found');
        }
        $text_fileset  = BibleFileset::uniqueFileset($text_fileset_id, $text_asset_id, 'text', true)->first();
        if (!$text_fileset) {
            return $this->setStatusCode(404)->replyWithError('Text Comparison Fileset not found');
        }
        $books = Book::all();

        // Create Sophia Query
        $query  = \DB::connection()->getPdo()->quote('+' . str_replace(' ', ' +', $query));
        $verses = BibleVerse::where('hash_id', $text_fileset->hash_id)
                     ->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))
                     ->when($book_id, function ($query) use ($book_id) {
                         return $query->where('book_id', $book_id);
                     })
                     ->select(['book_id', 'chapter'])
                     ->take(50)
                     ->get();

        // Create BibleFile Query
        $bible_files = BibleFile::query();
        $bible_files->where('hash_id', $audio_fileset->hash_id)->has('timestamps')->with('timestamps');
        foreach ($verses as $verse) {
            $current_book = $books->where('id', $verse->book_id)->first();
            $bible_files->orWhere([
                ['book_id', $current_book->id],
                ['chapter_start', $verse->chapter]
            ]);
        }
        $bible_files = $bible_files->limit(100)->get();
        return $this->reply(fractal($bible_files, new AudioTransformer()));
    }


    /**
     * Old path route for v2 of the API
     *
     * @version 2
     * @category v2_audio_location
     * @link http://api.dbp4.org/location - V2 Access
     * @link https://api.dbp.test/audio/location?key=TEST_KEY&v=4 - V2 Test Access
     *
     * @OA\Get(
     *     path="/audio/location",
     *     tags={"Library Audio"},
     *     summary="Returns Audio Server Information",
     *     description="This route offers information about the media distribution servers and the
               protocols they support. It is currently depreciated and only remains to account for
               the possibility that someone might still be using this old method of uri generation",
     *     operationId="v2_audio_timestamps",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="fileset_id", in="query", description="The specific fileset to return references for", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="book", in="query", description="The Book ID for which to return timestamps", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="chapter", in="query", description="The chapter for which to return timestamps", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_audio_timestamps")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_audio_timestamps")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v2_audio_timestamps"))
     *     )
     * )
     *
     * @return array
     *
     */
    public function location()
    {
        return $this->reply([
            [
                'server'    => 'dbp.test.s3.us-west-2.amazonaws.com',
                'root_path' => '/audio',
                'protocol'  => 'https',
                'CDN'       => '1',
                'priority'  => '5',
            ],
        ]);
    }
}
