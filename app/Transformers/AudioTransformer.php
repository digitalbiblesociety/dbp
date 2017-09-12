<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Bible\Audio;
class AudioTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = checkParam('v', null, 'optional') ?? 4;
		$this->iso = checkParam('iso', null, 'optional') ?? "eng";
	}
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($audio)
    {
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($audio);
		    case "2": return $this->transformForV2($audio);
		    case "4":
		    default: return $this->transformForV4($audio);
	    }
    }

	public function transformForV2($audio) {
    	switch(\Route::currentRouteName()) {
    		case "v2_audio_timestamps": {
			    return [
				    "verse_id"    => $audio->verse_start,
                    "verse_start" => $audio->timestamp
			    ];
		    }

		    case "v2_audio_path": {
			    return [
				    "book_id"    => $audio->book->id_osis,
				    "chapter_id" => $audio->chapter_start,
				    "path"       => $audio->filename
			    ];
		    }

	    }
		}

	public function transformForV4($audio) {
		return [
			"book_id"    => $audio->book->osis->code,
			"chapter_id" => $audio->chapter_start,
			"path"       => $audio->filename
		];
	}

}
