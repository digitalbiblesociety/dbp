<?php

namespace App\Transformers\V2;

use App\Models\Bible\BibleFileset;
use App\Transformers\BaseTransformer;

class LibraryCatalogTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(BibleFileset $fileset)
    {
	    switch($this->route) {
		    case "v2_library_volume": {
		    	$bible = $fileset->bible->first();
			    $bible_id = $fileset->bible->first()->id;
			    $language = $fileset->bible->first()->language;


			    if (strpos($fileset->set_type_code, 'P') !== false) {
				    $collection_code = "AL";
			    } else {
				    $collection_code = (substr($fileset->id,6,1) == "O") ? "OT" : "NT";
			    }

			    return [
				    "dam_id"                    => $fileset->id,
				    "fcbh_id"                   => $fileset->id,
				    "volume_name"               => $bible->currentTranslation->name,
				    "status"                    => "live", // for the moment these default to Live
				    "dbp_agreement"             => "true", // for the moment these default to True
				    "expiration"                => "0000-00-00",
				    "language_code"             => strtoupper($bible->iso),
				    "language_name"             => $language->autonym ?? "",
				    "language_english"          => $language->name ?? "",
				    "language_iso"              => $bible->iso,
				    "language_iso_2B"           => $language->iso2B ?? "",
				    "language_iso_2T"           => $language->iso2T ?? "",
				    "language_iso_1"            => $language->iso1 ?? "",
				    "language_iso_name"         => $language->name ?? "",
				    "language_family_code"      => ((@$language->parent) ? strtoupper(@$language->parent->iso) : strtoupper(@$language->iso)),
				    "language_family_name"      => ((@$language->parent) ? @$language->parent->autonym : @$language->name),
				    "language_family_english"   => ((@$language->parent) ? @$language->parent->name : @$language->name),
				    "language_family_iso"       => $fileset->iso ?? "",
				    "language_family_iso_2B"    => ((@$language->parent) ? @$language->parent->iso2B : @$language->iso2B) ?? "",
				    "language_family_iso_2T"    => ((@$language->parent) ? @$language->parent->iso2T : @$language->iso2T) ?? "",
				    "language_family_iso_1"     => ((@$language->parent) ? @$language->parent->iso1 : @$language->iso1) ?? "",
				    "version_code"              => substr($fileset->id,3) ?? "",
				    "version_name"              => "Wycliffe Bible Translators, Inc.",
				    "version_english"           => @$bible->currentTranslation->name ?? $fileset->id,
				    "collection_code"           => $collection_code,
				    "rich"                      => ($fileset->set_type_code == 'text_format') ? "1" : "0",
				    "collection_name"           => $fileset->name,
				    "updated_on"                => (string) $fileset->updated_at->toDateTimeString(),
				    "created_on"                => (string) $fileset->created_at->toDateTimeString(),
				    "right_to_left"             => (isset($bible->alphabet)) ? (($bible->alphabet->direction == "rtl") ? "true" : "false") : "false",
				    "num_art"                   => "0",
				    "num_sample_audio"          => "0",
				    "sku"                       => "",
				    "audio_zip_path"            => "",
				    "font"                      => null,
				    "arclight_language_id"      => "",
				    "media"                     => (strpos($fileset->set_type_code, 'audio') !== false) ? 'Audio' : 'Text',
				    "media_type"                => ($fileset->set_type_code == 'audio_drama') ? "true" : "false",
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
