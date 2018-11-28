<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Transformers\BaseTransformer;

class LibraryCatalogTransformer extends BaseTransformer
{

    public function transform($version)
    {
        $translations = optional($version->bible->first())->translations;
        $vernacular = $translations ? optional($translations->where('vernacular',1)->first())->name : '';
        $english = $translations ? optional($translations->where('language_id',$version->eng_id)->first())->name : '';
        return [
            'version_code' => substr($version->id,3),
            'version_name' => (string) $vernacular,
            'english_name' => (string) $english
        ];
    }
}
