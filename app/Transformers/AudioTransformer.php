<?php

namespace App\Transformers;

use App\Models\Bible\Audio;
class AudioTransformer extends BaseTransformer
{
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
    	switch($this->route) {
    		case "v2_audio_timestamps": {
			    return [
				    "verse_id"    => $audio->verse_start,
                    "verse_start" => $audio->timestamp
			    ];
		    }

		    case "v2_audio_path": {
			    return [
				    "book_id"    => $audio->book_id,
				    "chapter_id" => $audio->chapter_start,
				    "path"       => $audio->file_name
			    ];
		    }

	    }
		}

	public function transformForV4($audio) {
		return [
			"book_id"       => $audio->book_id,
			"chapter_start" => $audio->chapter_start,
			"chapter_end"   => $audio->chapter_end,
			"verse_start"   => $audio->verse_start,
			"verse_end"     => $audio->verse_end,
			"timestamp"     => $audio->timestamp,
			"path"          => $audio->file_name
		];
	}

}
