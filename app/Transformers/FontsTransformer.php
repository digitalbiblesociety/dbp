<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class FontsTransformer extends TransformerAbstract
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
			"base_url" => url(public_path('fonts/'.$font->fontFileName.'.ttf')),
			"files"    => [
				"zip"  => url(public_path('fonts/'.$font->fontFileName.'.zip')),
				"svg"  => url(public_path('fonts/'.$font->fontFileName.'.svg')),
				"ttf"  => url(public_path('fonts/'.$font->fontFileName.'.ttf')),
				   "platforms" => [
				       "android" => true,
				       "ios"     => true,
				       "web"     => true
				   ]
			]
		];
    }
}
