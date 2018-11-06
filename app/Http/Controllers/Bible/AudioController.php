<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;

use App\Models\Bible\Book;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFileTimestamp;

use App\Traits\CallsBucketsTrait;
use App\Transformers\AudioTransformer;

class AudioController extends APIController
{

	use CallsBucketsTrait;

	/**
	 *
	 * Returns an array of signed audio urls
	 *
	 * @version 4
	 * @category v2_audio_path
	 * @link http://api.dbp4.org/audio/path - V4 Access
	 * @link https://api.dbp.test/audio/path?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v4_alphabets.one - V4 Test Docs
	 *
	 * @OA\Get(
	 *     path="/audio/path",
	 *     tags={"Library Audio"},
	 *     summary="Returns Audio File path information",
	 *     description="This call returns the file path information for audio files within a volume
	 *         This information can be used with the response of the /audio/location call to create
	 *         a URI to retrieve the audio files.",
	 *     operationId="v2_audio_path",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="dam_id",     in="path",  description="The DAM ID for which to retrieve file path info.", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="chapter_id", in="query", description="The id for the specified chapter. If chapter is specified only the specified chapter audio information is returned to the caller.", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OA\Parameter(name="encoding",   in="query", description="The audio encoding format desired (No longer in use as Audio Files default to mp3).", @OA\Schema(type="string",title="encoding")),
	 *     @OA\Parameter(name="asset_id",   in="query", description="The bucket desired.", @OA\Schema(ref="#/components/schemas/Asset/properties/id")),
	 *     @OA\Parameter(name="book_id",    in="query", description="The USFM 2.4 book ID.", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_audio_path"))
	 *     )
	 * )
	 *
	 * @return mixed
	 *
	 */
	public function index()
	{
		// Check Params
		$fileset_id = checkParam('dam_id', null, 'optional');
		$chapter_id = checkParam('chapter_id', null, 'optional');
		$book_id    = checkParam('book_id', null, 'optional');
		$asset_id   = checkParam('bucket|bucket_id|asset_id', null, 'optional') ?? env('FCBH_AWS_BUCKET');

		// Account for various book ids
		if($book_id) {
			$book = Book::where('id', $book_id)->orWhere('id_osis', $book_id)->orWhere('id_usfx', $book_id)->first();
			$book_id = $book->id;
		}

		// Fetch the Fileset
		$fileset = BibleFileset::where('id', $fileset_id)->where('asset_id', $asset_id)->where('set_type_code', 'like', '%audio%')->first();
		if (!$fileset) $fileset = BibleFileset::where('id', substr($fileset_id, 0, -4))->where('asset_id', $asset_id)->where('set_type_code', 'like', '%audio%')->first();
		if (!$fileset) return $this->setStatusCode(404)->replyWithError('No Audio Fileset could be found for the code: ' . $fileset_id);

		// Fetch The files
		$audioChapters = BibleFile::with('book', 'bible')->where('hash_id', $fileset->hash_id)
		                          ->when($chapter_id, function ($query) use ($chapter_id) {
			                          return $query->where('chapter_start', $chapter_id);
		                          })->when($book_id, function ($query) use ($book_id) {
				return $query->where('book_id', $book_id);
			})->orderBy('file_name')->get();

		// Transaction id to be passed to signedUrl
		$transaction_id = random_int(0,10000000);
		foreach ($audioChapters as $key => $audio_chapter) {
			$audioChapters[$key]->file_name = $this->signedUrl('audio/' . $audio_chapter->bible->first()->id . '/' . $fileset_id . '/' . $audio_chapter->file_name,$asset_id,$transaction_id);
		}

		return $this->reply(fractal($audioChapters, new AudioTransformer())->serializeWith($this->serializer), [], $transaction_id);
	}

	/**
	 * Available Timestamps
	 *
	 * @OA\Get(
	 *     path="/timestamps",
	 *     tags={"Bibles"},
	 *     summary="Returns Bible Filesets which have Audio timestamps",
	 *     description="This call returns a list of hashes that have timestamp metadata associated
	 *         with them. This data could be used to search audio bibles for a specific term, make
	 *         karaoke verse & audio readings, or to jump to a specific location in an audio file.",
	 *     operationId="v4_timestamps",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(response=204, description="No timestamps are available at this time"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/hash_id")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/BibleFileset/properties/hash_id")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/BibleFileset/properties/hash_id"))
	 *     )
	 * )
	 *
	 * @return mixed
	 */
	public function availableTimestamps()
	{
		$hashes = BibleFile::has('timestamps')->select('hash_id')->distinct()->get();
		if($hashes->count() === 0) return $this->setStatusCode(204)->replyWithError('No timestamps are available at this time');
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
	 *         combination for a fileset. Note that the fileset id must be available via the path
	 *         `/timestamps`. At first, only a few filesets may have timestamps metadata applied.",
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
	 * @param $id
	 * @param $book
	 * @param $chapter
	 *
	 * @return mixed
	 */
	public function timestampsByReference($id, $book, $chapter)
	{
		// Check Params
		$id      = checkParam('fileset_id', $id);
		$type    = checkParam('type');
		$book    = checkParam('book', $book);
		$chapter = checkParam('chapter', $chapter);

		// Fetch Fileset & Files
		$fileset = BibleFileset::where('id',$id)->where('fileset_size_code',$type)->first();
		if(!$fileset) return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));
		$bible_files = BibleFile::where('hash_id',$fileset->hash_id)->where('book',$book)->where('chapter',$chapter)->get();

		// Fetch Timestamps
		$audioTimestamps = BibleFileTimestamp::whereIn('file_id', $bible_files->pluck('id'))->orderBy('verse_start')->get();

		// Return Response
		return $this->reply(fractal($audioTimestamps,new AudioTransformer(),$this->serializer));
	}


	/**
	 * Returns a List of timestamps for a given word
	 *
	 * @OA\Get(
	 *     path="/timestamps/search",
	 *     tags={"Bibles"},
	 *     summary="Returns audio timestamps for a specific word",
	 *     description="This route will search the text for a specific word or phrase and return a
	 *         collection of timestamps associated with the verse references connected to the term",
	 *     operationId="v4_timestamps.tag",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The specific fileset to return references for", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="query", in="query", required=true, description="The tag for which to return timestamps", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_timestamps_tag")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_timestamps_tag")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_timestamps_tag"))
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
		$text_fileset_id  = checkParam('text_fileset_id');
		$book_id          = checkParam('book_id', null, 'optional');
		$query            = checkParam('query');

		// Fetch Fileset & Books
		$audio_fileset = BibleFileset::where('set_type_code', 'LIKE','audio%')->where('id',$audio_fileset_id)->first();
		if(!$audio_fileset) return $this->setStatusCode(404)->replyWithError('Audio Fileset not found');
		$text_fileset  = BibleFileset::where('set_type_code','text_plain')->where('id',$text_fileset_id)->first();
		if(!$text_fileset) return $this->setStatusCode(404)->replyWithError('Text Comparison Fileset not found');
		$books = Book::all();

		// Create Sophia Query
		$query  = \DB::connection()->getPdo()->quote('+' . str_replace(' ', ' +', $query));
		$verses = \DB::connection('sophia')->table($text_fileset->id.'_vpl')
		             ->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))
					 ->when($book_id, function ($query) use ($books,$book_id) {
						 $current_book = $books->where('id',$book_id)->first();
					 	return $query->where('book', $current_book->id_usfx);
					 })
		             ->select(['book', 'chapter'])
					 ->take(50)
		             ->get();

		// Create BibleFile Query
		$bible_files = BibleFile::query();
		$bible_files->where('hash_id',$audio_fileset->hash_id)->has('timestamps')->with('timestamps');
		foreach ($verses as $verse) {
			$current_book = $books->where('id_usfx',$verse->book)->first();
			$bible_files->orWhere([
				['book_id', $current_book->id],
				['chapter_start', $verse->chapter]
			]);
		}
		$bible_files = $bible_files->limit(100)->get();
		return $this->reply(fractal($bible_files,new AudioTransformer()));
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
	 *         protocols they support. It is currently depreciated and only remains to account for
	 *         the possibility that someone might still be using this old method of uri generation",
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
