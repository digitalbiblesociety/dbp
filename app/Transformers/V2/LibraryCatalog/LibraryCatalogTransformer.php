<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Transformers\BaseTransformer;

class LibraryCatalogTransformer extends BaseTransformer
{

	public function transform($version)
	{
		switch ($this->route) {
			case "v2_library_version": {
				return [
					'version_code' => $version->id,
					'version_name' => $version->bible->first()->vernacularTranslation->name ?? '',
					'english_name' => $version->bible->first()->currentTranslation->name ?? ''
				];
				break;
			}
		}
	}

}