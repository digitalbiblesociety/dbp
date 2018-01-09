<?php

namespace App\Transformers;

class FontsTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($font)
    {
        return [
			"id"       => $font->id,
			"name"     => $font->fontName,
			"base_url" => 'https://cdn.bible.build/fonts/'.$font->fontFileName.'.ttf',
			"files"    => [
				"zip"  => 'https://cdn.bible.build/fonts/'.$font->fontFileName.'.zip',
				"svg"  => 'https://cdn.bible.build/fonts/'.$font->fontFileName.'.svg',
				"ttf"  => 'https://cdn.bible.build/fonts/'.$font->fontFileName.'.ttf',
				   "platforms" => [
				       "android" => true,
				       "ios"     => true,
				       "web"     => true
				   ]
			]
		];
    }
}
