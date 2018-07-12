<?php

namespace App\Transformers\V2;

use App\Models\Bible\BibleFileset;
use App\Transformers\BaseTransformer;

class LibraryCatalogTransformer extends BaseTransformer
{

    public function transform(BibleFileset $fileset)
    {
	    switch($this->route) {
		    case "v2_library_volume": {
		    	$bible = $fileset->bible->first();
			    $bible_id = $fileset->bible->first()->id;
			    $language = $fileset->bible->first()->language;

			    $ver_title = @$bible->translatedTitles->where('language_id',$language->id)->first()->name;
			    $eng_title = @$bible->translatedTitles->where('language_id','eng')->first()->name;

			    $font_array = [
				    "id" => "12",
				    "name" => "Charis SIL",
				    "base_url" => "http://cloud.faithcomesbyhearing.com/fonts/Charis_SIL",
				    "files" => [
					    "zip" => "all.zip",
					    "ttf" => "font.ttf"
				    ],
				    "platforms" => [
					    "android" => true,
					    "ios" => true,
					    "web" => true
				    ],
				    "copyright" => "&copy; 2000-2013, SIL International  ",
				    "url" => "http://bit.ly/1uKBBMx"
			    ];

			    if (strpos($fileset->set_type_code, 'P') !== false) {
				    $collection_code = "AL";
			    } else {
				    $collection_code = (substr($fileset->id,6,1) == "O") ? "OT" : "NT";
			    }

			    /**
			     * @OAS\Schema (
			     *	type="array",
			     *	schema="v2_library_volume",
			     *	description="The v2_library_volume",
			     *	title="v2_library_volume",
			     *	@OAS\Xml(name="v2_library_volume"),
			     *	@OAS\Items(
			     *              @OAS\Property(property="dam_id",                    ref="#/components/schemas/BibleFileset/id"),
			     *              @OAS\Property(property="fcbh_id",                   ref="#/components/schemas/BibleEquivalent/equivalent_id"),
			     *              @OAS\Property(property="volume_name",               ref="#/components/schemas/BibleTranslation/name"),
			     *              @OAS\Property(property="status",                    @OAS\Schema(type="string",description="A leftover from the original v2, will always be `live`")),
			     *              @OAS\Property(property="dbp_agreement",             @OAS\Schema(type="string",description="A leftover from the original v2, will always be `true`")),
			     *              @OAS\Property(property="expiration",                @OAS\Schema(type="string",description="A leftover from the original v2, will always be `0000-00-00`")),
			     *              @OAS\Property(property="language_code",             ref="#/components/schemas/Language/iso"),
			     *              @OAS\Property(property="language_name",             ref="#/components/schemas/LanguageTranslation/name"),
			     *              @OAS\Property(property="language_english",          ref="#/components/schemas/LanguageTranslation/name"),
			     *              @OAS\Property(property="language_iso",              ref="#/components/schemas/Language/iso"),
			     *              @OAS\Property(property="language_iso_2B",           ref="#/components/schemas/Language/iso2B"),
			     *              @OAS\Property(property="language_iso_2T",           ref="#/components/schemas/Language/iso2T"),
			     *              @OAS\Property(property="language_iso_1",            ref="#/components/schemas/Language/iso1"),
			     *              @OAS\Property(property="language_iso_name",         ref="#/components/schemas/Language/name"),
			     *              @OAS\Property(property="language_family_code",      ref="#/components/schemas/Language/iso"),
			     *              @OAS\Property(property="language_family_name",      ref="#/components/schemas/Language/name"),
			     *              @OAS\Property(property="language_family_english",   ref="#/components/schemas/Language/name"),
			     *              @OAS\Property(property="language_family_iso",       ref="#/components/schemas/Language/iso"),
			     *              @OAS\Property(property="language_family_iso_2B",    ref="#/components/schemas/Language/iso2B"),
			     *              @OAS\Property(property="language_family_iso_2T",    ref="#/components/schemas/Language/iso2T"),
			     *              @OAS\Property(property="language_family_iso_1",     ref="#/components/schemas/Language/iso1"),
			     *              @OAS\Property(property="version_code",              @OAS\Schema(type="string",example="KJV")),
			     *              @OAS\Property(property="version_name",              ref="#/components/schemas/BibleTranslation/name"),
			     *              @OAS\Property(property="version_english",           ref="#/components/schemas/BibleTranslation/name"),
			     *              @OAS\Property(property="collection_code",           @OAS\Schema(type="string",example="OT")),
			     *              @OAS\Property(property="rich",                      @OAS\Schema(type="string",example="0")),
			     *              @OAS\Property(property="collection_name",           @OAS\Schema(type="string",example="OT")),
			     *              @OAS\Property(property="updated_on",                ref="#/components/schemas/BibleFileset/updated_on"),
			     *              @OAS\Property(property="created_on",                ref="#/components/schemas/BibleFileset/created_on"),
			     *              @OAS\Property(property="right_to_left",             ref="#/components/schemas/Alphabet/direction"),
			     *              @OAS\Property(property="num_art",                   @OAS\Schema(type="string",example="OT")),
			     *              @OAS\Property(property="num_sample_audio",          @OAS\Schema(type="string")),
			     *              @OAS\Property(property="sku",                       @OAS\Schema(type="string")),
			     *              @OAS\Property(property="audio_zip_path",            @OAS\Schema(type="string")),
			     *              @OAS\Property(property="font",                      ref="#/components/schemas/AlphabetFont"),
			     *              @OAS\Property(property="arclight_language_id",      @OAS\Schema(type="string")),
			     *              @OAS\Property(property="media",                     @OAS\Schema(type="string")),
			     *              @OAS\Property(property="media_type",                @OAS\Schema(type="string")),
			     *              @OAS\Property(property="delivery",                  @OAS\Schema(type="string"))
			     *     )
			     *   )
			     * )
			     */
			    return [
				    "dam_id"                    => (string) $fileset->generated_id,
				    "fcbh_id"                   => (string) $fileset->generated_id,
				    "volume_name"               => (string) $ver_title,
				    "status"                    => "live", // for the moment these default to Live
				    "dbp_agreement"             => "true", // for the moment these default to True
				    "expiration"                => "0000-00-00",
				    "language_code"             => (string) strtoupper($bible->iso),
				    "language_name"             => (string) $language->autonym ?? $language->name,
				    "language_english"          => (string) $language->name,
				    "language_iso"              => (string) $bible->iso,
				    "language_iso_2B"           => (string) $language->iso2B,
				    "language_iso_2T"           => (string) $language->iso2T,
				    "language_iso_1"            => (string) $language->iso1,
				    "language_iso_name"         => (string) $language->name,
				    "language_family_code"      => (string) ((@$language->parent) ? strtoupper(@$language->parent->iso) : strtoupper($language->iso)),
				    "language_family_name"      => (string) ((@$language->parent) ? @$language->parent->autonym : $language->name),
				    "language_family_english"   => (string) ((@$language->parent) ? @$language->parent->name : $language->name),
				    "language_family_iso"       => (string) $bible->iso,
				    "language_family_iso_2B"    => (string) ((@$language->parent) ? @$language->parent->iso2B : @$language->iso2B) ?? $language->iso2B,
				    "language_family_iso_2T"    => (string) ((@$language->parent) ? @$language->parent->iso2T : @$language->iso2T) ?? $language->iso2T,
				    "language_family_iso_1"     => (string) ((@$language->parent) ? @$language->parent->iso1 : @$language->iso1) ?? $language->iso1,
				    "version_code"              => (string) substr($fileset->id,3,3),
				    "version_name"              => (string) $ver_title ?? $eng_title,
				    "version_english"           => (string) $eng_title ?? $ver_title,
				    "collection_code"           => (string) $collection_code,
				    "rich"                      => (string) ($fileset->set_type_code == 'text_format') ? "1" : "0",
				    "collection_name"           => (string) ($collection_code == "NT") ? "New Testament" : "Old Testament",
				    "updated_on"                => (string) $fileset->updated_at->toDateTimeString(),
				    "created_on"                => (string) $fileset->created_at->toDateTimeString(),
				    "right_to_left"             => (isset($bible->alphabet)) ? (($bible->alphabet->direction == "rtl") ? "true" : "false") : "false",
				    "num_art"                   => "0",
				    "num_sample_audio"          => "0",
				    "sku"                       => "",
				    "audio_zip_path"            => "",
				    "font"                      => (@$bible->alphabet->requires_font) ? $font_array : null,
				    "arclight_language_id"      => "",
				    "media"                     => (strpos($fileset->set_type_code, 'audio') !== false) ? 'Audio' : 'Text',
				    "media_type"                => ($fileset->set_type_code == 'audio_drama') ? "Drama" : "Non-Drama",
				    "delivery"                  => [
					    "mobile",
					    "web",
					    "local_bundled",
					    "subsplash"
				    ],
				    "resolution"                => []
			    ];
			    break;
		    }
		    default: return [];
	    }
    }
}
