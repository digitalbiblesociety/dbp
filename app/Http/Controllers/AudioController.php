<?php

namespace App\Http\Controllers;

use App\Models\Bible\Audio;
use App\Models\Bible\BibleFileTimestamp;
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
    	$audioChapters = Audio::with('book')->where('bible_id',$id)->orderBy('filename')->get();
        return $this->reply(fractal()->collection($audioChapters)->transformWith(new AudioTransformer()));
    }

	public function timestamps($id = null,$chapter = null,$book = null)
	{
		// Just some error checking
		$id = CheckParam('dam_id',$id);
		$chapter = CheckParam('chapter',$chapter);
		$book = CheckParam('book',$book);

		$audioTimestamps = Audio::with('book')->where('bible_id', $id)
		                                        ->where('chapter_start', $chapter)->orderBy('filename')
												->where('book_id', $book)->first()->references;
		return $this->reply(fractal()->collection($audioTimestamps)->transformWith(new AudioTransformer()));
	}

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
