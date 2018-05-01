<?php

namespace App\Http\Controllers;

use App\Models\Bible\Book;
use App\Models\Bible\Text;

use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFileTimestamp;
use App\Transformers\AudioTransformer;

use App\Helpers\AWS\Bucket;


class AudioController extends APIController
{

	public function index($id = null)
	{
		$bible_id = CheckParam('dam_id',$id);
		$chapter_id = CheckParam('chapter_id',null,'optional');
		$book_id = CheckParam('book_id',null,'optional');
		$bucket_id =  CheckParam('bucket_id',null,'optional') ?? env('FCBH_AWS_BUCKET');
		if($book_id) $book = Book::where('id',$book_id)->orWhere('id_osis',$book_id)->orWhere('id_usfx',$book_id)->first();
		if(isset($book)) $book_id = $book->id;
		$fileset = BibleFileset::where('id', $bible_id)->where('bucket_id',$bucket_id)->where('set_type_code', 'like', '%audio%')->first();
		if(!$fileset) return $this->setStatusCode(404)->replyWithError("No Audio Fileset could be found for the code: ".$bible_id);

		$audioChapters = BibleFile::with('book')->where('hash_id',$fileset->hash_id)
		                          ->when($chapter_id, function ($query) use ($chapter_id) {
			                          return $query->where('chapter_start', $chapter_id);
		                          })->when($book_id, function ($query) use ($book_id) {
				return $query->where('book_id', $book_id);
			})->orderBy('file_name')->get();

		foreach ($audioChapters as $key => $audio_chapter) {
			$audioChapters[$key]->file_name = Bucket::signedUrl('audio/'.$bible_id.'/'.$audio_chapter->file_name);
		}
		return $this->reply(fractal()->collection($audioChapters)->serializeWith($this->serializer)->transformWith(new AudioTransformer()));
	}

	/**
	 * Available Timestamps
	 *
	 * @return mixed
	 */
	public function availableTimestamps()
	{
		$bibleFile = BibleFile::has('timestamps')->select('hash_id')->distinct()->get();
		return $this->reply($bibleFile);
	}

	/**
	 * Returns a List of timestamps for a given Scripture Reference
	 *
	 * @param string $id
	 * @param string $book
	 * @param int $chapter
	 *
	 * @return mixed
	 */
	public function timestampsByReference(string $id = null, string $book = null, int $chapter = null)
	{
		// Set Params
		$id = CheckParam('fileset_id', $id);
		$chapter = CheckParam('chapter', $chapter);
		$book = CheckParam('book', $book);

		// Fetch timestamps
		return $this->reply(BibleFileTimestamp::
			select(['bible_file_id as verse_id','verse_start','timestamp'])
			->where('bible_fileset_id', $id)
			->where('chapter_start', $chapter)
			->where('book_id', $book)->orderBy('chapter_start')->orderBy('verse_start')->get());

		// Return API
		//return $this->reply(fractal()->collection($audioTimestamps)->serializeWith($this->serializer)->transformWith(new AudioTransformer()));
	}


	/**
	 * Returns a List of timestamps for a given tag
	 *
	 * @param string $id
	 * @param string $query
	 *
	 * @return mixed
	 */
	public function timestampsByTag(string $id = "", string $query = "")
	{
		// Check Params
		$id = CheckParam('dam_id', $id);
		$query = CheckParam('query', $query);
		
		$query = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query));
		$verses = \DB::connection('sophia')->table($id)
			->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))
			->select(['book','chapter'])
			->get();

		// Build the timestamp query
		$timestamps = BibleFileTimestamp::query();
		foreach ($verses as $verse) $timestamps->orWhere([['book_id', '=', $verse->book_id],['chapter_start', '=', $verse->chapter_number]]);
		$timestamps = $timestamps->limit(500)->get();

		return $this->reply(fractal()->collection($timestamps)->transformWith(new AudioTransformer()));
	}


	/**
	 * Old path route for v2 of the API
	 *
	 * @version 2
	 * @category v2_audio_location
	 * @link http://api.bible.build/location - V4 Access
	 * @link https://api.dbp.dev/audio/location?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v2#/Audio/v2_audio_location - V4 Test Docs
	 *
	 * @return array
	 *
	 */
	public function location()
	{
		return $this->reply([
				[
					"server"    => "dbp-dev.s3.us-west-2.amazonaws.com/",
					"root_path" => "/audio",
					"protocol"  => "https",
					"CDN"       => "1",
					"priority"  => "5"
				]
			]);
	}

}
