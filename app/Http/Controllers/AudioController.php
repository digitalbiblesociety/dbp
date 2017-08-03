<?php

namespace App\Http\Controllers;

use App\Models\Bible\Audio;
use App\Models\Bible\AudioReferences;
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
    	$audioChapters = Audio::with('book.osis')->where('bible_id',$id)->orderBy('filename')->get();
        return $this->reply(fractal()->collection($audioChapters)->transformWith(new AudioTransformer()));
    }

	public function timestamps($id = null,$chapter = null,$book = null)
	{
		// Just some error checking
		$id = CheckParam('dam_id',$id);
		$chapter = CheckParam('chapter',$chapter);
		$book = CheckParam('book',$book);

		$audioChapter = Audio::with('book.osis')->where('bible_id',$id)
		                                        ->where('chapter_start',$chapter)->orderBy('filename')
												->where('book_id',$book)->first();
		$audioTimestamps = $audioChapter->references;
		return $this->reply(fractal()->collection($audioTimestamps)->transformWith(new AudioTransformer()));
	}


}
