<?php

namespace App\Transformers;

use App\Models\Bible\Bible;
class BibleTransformer extends BaseTransformer
{

	/**
	 * A Fractal transformer.
	 *
	 * @param Bible $bible
	 *
	 * @return array
	 */
    public function transform(Bible $bible)
    {
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($bible);
		    case "2": return $this->transformForV2($bible);
		    case "3": return $this->transformForV2($bible);
		    case "4": return $this->transformForV4($bible);
		    default: return $this->transformForV4($bible);
	    }
    }

    public function transformForV2($bible)
    {

    	    switch($this->route) {

		        case "v2_library_metadata": {
			        return [
				        "dam_id"         => $bible->id,
				        "mark"           => $bible->copyright,
				        "volume_summary" => null,
				        "font_copyright" => null,
				        "font_url"       => (isset($bible->alphabet->primaryFont)) ? $bible->alphabet->primaryFont->fontFileName : null,
				        "organization"   => $bible->organizations
			        ];
			        break;
		        }

		        case "v2_volume_history": {
		        	return [
		        		"dam_id" => $bible->id,
				        "time"   => $bible->updated_at->toDateTimeString(),
				        "event"  => "Updated"
			        ];
			        break;
		        }

		        case "v2_library_volume": {
		        	foreach($bible->filesets as $fileset) {
				        return [
					        "dam_id"                    => $fileset->id,
					        "fcbh_id"                   => $fileset->id,
					        "volume_name"               => @$bible->currentTranslation->name ?? "",
					        "status"                    => "live", // for the moment these default to Live
					        "dbp_agreement"             => "true", // for the moment these default to True
					        "expiration"                => "0000-00-00",
					        "language_code"             => strtoupper($bible->iso) ?? "",
					        "language_name"             => $bible->language->autonym ?? $bible->language->name,
					        "language_english"          => $bible->language->name ?? "",
					        "language_iso"              => $bible->iso ?? "",
					        "language_iso_2B"           => @$bible->language->iso2B ?? "",
					        "language_iso_2T"           => @$bible->language->iso2T ?? "",
					        "language_iso_1"            => @$bible->language->iso1 ?? "",
					        "language_iso_name"         => @$bible->language->name ?? "",
					        "language_family_code"      => (($bible->language->parent) ? strtoupper($bible->language->parent->parentLanguage->iso) : strtoupper($bible->iso)) ?? "",
					        "language_family_name"      => (($bible->language->parent) ? $bible->language->parent->parentLanguage->autonym : $bible->language->name) ?? "",
					        "language_family_english"   => (($bible->language->parent) ? $bible->language->parent->parentLanguage->name : $bible->language->name) ?? "",
					        "language_family_iso"       => $bible->iso ?? "",
					        "language_family_iso_2B"    => (($bible->language->parent) ? $bible->language->parent->parentLanguage->iso2B : $bible->language->iso2B) ?? "",
					        "language_family_iso_2T"    => (($bible->language->parent) ? $bible->language->parent->parentLanguage->iso2T : $bible->language->iso2T) ?? "",
					        "language_family_iso_1"     => (($bible->language->parent) ? $bible->language->parent->parentLanguage->iso1 : $bible->language->iso1) ?? "",
					        "version_code"              => substr($bible->id,3) ?? "",
					        "version_name"              => @$bible->vernacularTranslation->name ?? @$bible->currentTranslation->name,
					        "version_english"           => @$bible->currentTranslation->name ?? $bible->id,
					        "collection_code"           => ($fileset->name == "Old Testament") ? "OT" : "NT",
					        "rich"                      => "0",
					        "collection_name"           => $fileset->name,
					        "updated_on"                => "".$bible->updated_at->toDateTimeString() ?? "",
					        "created_on"                => "".$bible->created_at->toDateTimeString() ?? "",
					        "right_to_left"             => ($bible->alphabet->direction == "rtl") ? "true" : "false",
					        "num_art"                   => "0",
					        "num_sample_audio"          => "0",
					        "sku"                       => "",
					        "audio_zip_path"            => "",
					        "font"                      => null,
					        "arclight_language_id"      => "",
					        "media"                     => $fileset->set_type,
					        "media_type"                => "Drama",
					        "delivery"                  => [
					        	"mobile",
						        "web",
						        "local_bundled",
						        "subsplash"
					        ],
					        "resolution"                => []
				        ];
			        }
		        }

	        }


    }

	public function transformForV4($bible)
	{

		switch($this->route) {

			case "v4_bible.all": {
				return [
					"abbr"         => $bible->id,
					"mark"         => $bible->copyright,
					"name"         => $bible->currentTranslation->name,
					"vname"        => @$bible->vernacularTranslation->name ?? "",
					"organization" => $bible->organization,
					"language"     => $bible->language->name,
					"date"         => intval( $bible->date ),
					"country"      => $bible->language->primaryCountry->name ?? '',
				];
			}

			case "v4_bible.one": {
				return $bible;
			}

		}
	}

	public function transformForDataTables($bible)
	{
		return [
			'<a href="/bibles/'.$bible->id.'">'.$bible->currentTranslation->name.'</a>',
			@$bible->vernacularTranslation->name ?? "",
			$bible->language->primaryCountry->name ?? "",
			$bible->language->name ?? "",
			$bible->date,
			$bible->id,
			$bible->language->iso,
		];
	}


}
