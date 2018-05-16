<?php

namespace App\Transformers;

class FontsTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="font_response",
	 *     description="The full alphabet return for the single alphabet route",
	 *     title="The single alphabet response",
	 *     @OAS\Xml(name="v4_alphabets_one_response"),
	 *     @OAS\Property(property="id",                     ref="#/components/schemas/AlphabetFont/properties/id"),
	 *     @OAS\Property(property="name",                   ref="#/components/schemas/AlphabetFont/properties/fontFileName"),
	 *     @OAS\Property(property="base_url",               @OAS\Items(type="string")),
	 *     @OAS\Property(property="files",                  @OAS\Items(type="object"))
	 * )
	 *
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
