<?php

namespace App\Http\Controllers;

use App\Models\Bible\Text;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileTimestamp;
use App\Transformers\AudioTransformer;

class AudioController extends APIController
{

	/**
	 * @param null $id
	 *
	 * @return mixed
	 */
	public function index($id = null)
    {
	    $id = CheckParam('fileset_id',$id, 'optional');
		if(!$id) $id = CheckParam('dam_id',$id,'optional');
		if(!$id) return $this->setStatusCode(422)->replyWithError("Missing dam_id param");
	    
	    $chapter_id = CheckParam('chapter_id',null,'optional');
	    $book_id = CheckParam('book_id',null,'optional');

    	$audioChapters = BibleFile::with('book')->where('set_id',$id)->when($chapter_id, function ($query) use ($chapter_id) {
		    return $query->where('chapter_start', $chapter_id);
	    })->when($book_id, function ($query) use ($book_id) {
		    return $query->where('book_id', $book_id);
	    })->orderBy('file_name')->get();
        return $this->reply(fractal()->collection($audioChapters)->transformWith(new AudioTransformer()));
    }

	/**
	 * Available Timestamps
	 *
	 * @return mixed
	 */
	public function availableTimestamps()
	{
		return BibleFile::has('timestamps')->select('bible_id')->get()->pluck('bible_id')->unique();
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
		$audioTimestamps = BibleFileTimestamp::where('bible_fileset_id', $id)
		                            ->where('chapter_start', $chapter)
		                            ->where('book_id', $book)->orderBy('chapter_start')->orderBy('verse_start')->get();

		// Return API
		return $this->reply(fractal()->collection($audioTimestamps)->serializeWith($this->serializer)->transformWith(new AudioTransformer()));
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
	 * @return mixed
	 */
	public function location()
	{
		return $this->reply([
			[
				"server" => "s3-us-west-2.amazonaws.com",
				"root_path" => "/dbp-dev/audio",
				"protocol" => "https",
				"CDN" => 1,
				"priority" => 5
			]
		]);
	}

}
