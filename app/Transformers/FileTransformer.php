<?php

namespace App\Transformers;

use App\Models\Bible\BibleFile;

class FileTransformer extends BaseTransformer
{
	/**
	 * A Fractal transformer.
	 *
	 * @param BibleFile $file
	 *
	 * @return array
	 */
	public function transform(BibleFile $file)
	{
		switch ((int) $this->version) {
			case 2: return $this->transformForV2($file);
			case 3: return $this->transformForV3($file);
			case 4:
			default: return $this->transformForV4($file);
		}
	}

	public function transformForV3($file)
	{
		$manufactured_id = random_int(0,20000);
		return [
			'id'         => (string) $manufactured_id,
			'number'     => $file->chapter_start,
			'sort_order' => $file->chapter_start,
			'enabled'    => '1',
			'created_at' => $file->created_at->toDateTimeString(),
			'updated_at' => $file->updated_at->toDateTimeString(),
			'book_id'    => $manufactured_id,
			'dam_id'     => $file->set_id,
			'book_code'  => $file->book_id,
			'audio_path' => $file->file_name,
		];
	}

}
