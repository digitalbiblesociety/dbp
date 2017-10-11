<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileTimestamp;

use App\Models\Bible\Text;

use App\Transformers\AudioTransformer;
use Illuminate\Http\Request;

class AudioController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
	    $id = CheckParam('dam_id',$id);
    	$audioChapters = BibleFile::with('book')->where('bible_id',$id)->orderBy('file_name')->get();
        return $this->reply(fractal()->collection($audioChapters)->transformWith(new AudioTransformer()));
    }

	/**
	 * Available Timestamps
	 *
	 * @return JSON
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
	 * @return View|JSON
	 */
	public function timestampsByReference(string $id = null, string $book = null, int $chapter = null)
	{
		// Set Params
		$id = CheckParam('dam_id', $id);
		$chapter = CheckParam('chapter', $chapter);
		$book = CheckParam('book', $book);

		// Fetch timestamps
		$audioTimestamps = BibleFileTimestamp::where('bible_id', $id)
		                            ->where('chapter_start', $chapter)
		                            ->where('book_id', $book)->orderBy('chapter_start')->orderBy('verse_start')->get();

		// Return API
		return $this->reply(fractal()->collection($audioTimestamps)->transformWith(new AudioTransformer()));
	}

	/**
	 * Returns a List of timestamps for a given tag
	 *
	 * @param string $id
	 * @param string $query
	 *
	 * @return JSON|View
	 */
	public function timestampsByTag(string $id = null, string $query = null)
	{
		// Check Params
		$id = CheckParam('dam_id', $id);
		$query = CheckParam('query', $query);
		
		$query = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query));
		$verses = Text::where('bible_id', $id)
			->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))
			->select('bible_id','book_id','chapter_number')
			->get();

		// Build the timestamp query
		$timestamps = BibleFileTimestamp::query();
		foreach ($verses as $verse) $timestamps->orWhere([['book_id', '=', $verse->book_id],['chapter_start', '=', $verse->chapter_number]]);
		$timestamps = $timestamps->limit(500)->get();

		return $this->reply(fractal()->collection($timestamps)->transformWith(new AudioTransformer()));
	}

	/**
	 * Returns the location routes
	 *
	 * @return JSON
	 */
	public function location()
	{
		return $this->reply([
			[
				"server" => "cloud.faithcomesbyhearing.com",
				"root_path" => "/mp3audiobibles2",
				"protocol" => "http",
				"CDN" => 1,
				"priority" => 5
			],
			[
				"server"    => "fcbhabdm.s3.amazonaws.com",
				"root_path" => "/mp3audiobibles2",
				"protocol"  => "http",
				"CDN"       => 0,
				"priority"  => 6
			],
			[
				"server" => "cdn.faithcomesbyhearing.com",
				"root_path" => "/cfx/st",
				"protocol" => "rtmp-amazon",
				"CDN" => 0,
				"priority" => 9
			]
		]);
	}

}
