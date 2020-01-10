<?php

namespace App\Transformers;

class FontsTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @param $font
     *
     * @return array
     * @OA\Schema (
     *     type="object",
     *     schema="font_response",
     *     description="The full alphabet return for the single alphabet route",
     *     title="The single alphabet response",
     *     @OA\Xml(name="v4_alphabets_one_response"),
     *     @OA\Property(property="id",                     ref="#/components/schemas/AlphabetFont/properties/id"),
     *     @OA\Property(property="name",                   ref="#/components/schemas/AlphabetFont/properties/fontFileName"),
     *     @OA\Property(property="base_url",               type="string"),
     *     @OA\Property(property="files",                  type="object",
     *          @OA\Property(property="zip",               type="string"),
     *          @OA\Property(property="svg",               type="string"),
     *          @OA\Property(property="ttf",               type="string"),
     *          @OA\Property(property="platforms",         type="object",
     *             @OA\Property(property="android",           type="boolean"),
     *             @OA\Property(property="ios",               type="boolean"),
     *             @OA\Property(property="web",               type="boolean"),
     *       )
     *     )
     * )
     */
    public function transform($font)
    {
        return [
            'id'       => $font->id,
            'name'     => $font->fontName,
            'base_url' => 'https://cdn.bible.build/fonts/' . $font->fontFileName . '.ttf',
            'files'    => [
                'zip'       => 'https://cdn.bible.build/fonts/' . $font->fontFileName . '.zip',
                'svg'       => 'https://cdn.bible.build/fonts/' . $font->fontFileName . '.svg',
                'ttf'       => 'https://cdn.bible.build/fonts/' . $font->fontFileName . '.ttf',
                'platforms' => [
                       'android' => true,
                       'ios'     => true,
                       'web'     => true
                   ]
            ]
        ];
    }
}
