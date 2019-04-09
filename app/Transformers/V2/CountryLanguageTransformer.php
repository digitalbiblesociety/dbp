<?php

namespace App\Transformers\V2;

use League\Fractal\TransformerAbstract;

class CountryLanguageTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_language)
    {
        /**
         * @OA\Schema (
         *  type="array",
         *  schema="v2_country_lang",
         *  description="The v2_country_lang response",
         *  title="v2_country_lang",
         *  @OA\Xml(name="v2_country_lang"),
         *  @OA\Items(
         *          @OA\Property(property="id",                    ref="#/components/schemas/Language/properties/id"),
         *          @OA\Property(property="lang_code",             ref="#/components/schemas/Language/properties/iso"),
         *          @OA\Property(property="region",                ref="#/components/schemas/Language/properties/area"),
         *          @OA\Property(property="country_primary",       ref="#/components/schemas/Language/properties/country_id"),
         *          @OA\Property(property="lang_id",               ref="#/components/schemas/Language/properties/iso2B"),
         *          @OA\Property(property="iso_language_code",     ref="#/components/schemas/Language/properties/iso2T"),
         *          @OA\Property(property="regional_lang_name",    ref="#/components/schemas/Language/properties/iso1"),
         *          @OA\Property(property="family_id",             ref="#/components/schemas/Language/properties/name"),
         *          @OA\Property(property="primary_country_name",  ref="#/components/schemas/Language/properties/iso2T"),
         *          @OA\Property(property="country_image",         @OA\Schema(type="string",example="https://cdn.bible.build/img/flags/full/80X60/in.png")),
         *          @OA\Property(property="country_additional", @OA\Schema(type="string",required="false",example="BM: CH: CN: MM",description="The country names are delimited by both a colon and a space"))
         *     )
         *   )
         * )
         */

        return [
            'id'                   => (string) $country_language->language->id,
            'lang_code'            => (string) $country_language->language->iso,
            'region'               => (string) $country_language->language->area,
            'country_primary'      => strtoupper($country_language->country_id),
            'lang_id'              => strtoupper($country_language->language->iso),
            'iso_language_code'    => strtoupper($country_language->language->iso),
            'regional_lang_name'   => $country_language->language->autonym ?? $country_language->language->name,
            'family_id'            => strtoupper($country_language->language->iso),
            'primary_country_name' => (string) optional($country_language->country)->name,
            'country_image'        => $country_language->country_image,
            'country_additional'   => optional($country_language)->language->relationLoaded('countries') ? strtoupper($country_language->language->countries->pluck('id')->implode(': ')) : ''
        ];
    }
}
