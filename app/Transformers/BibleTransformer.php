<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Bible\Bible;
class BibleTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = checkParam('v', null, 'optional') ?? 4;
		$this->iso = checkParam('iso', null, 'optional') ?? "eng";
	}

	/**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Bible $bible)
    {
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($bible);
		    case "2": return $this->transformForV2($bible);
		    case "4":
		    default: return $this->transformForV4($bible);
	    }
    }

    public function transformForV2($bible)
    {
    	    $route = \Route::currentRouteName();
    	    switch($route) {

		        case "v2_volume_history": {
		        	return [
		        		"dam_id" => $bible->id,
				        "time"   => $bible->updated_at->toDateTimeString(),
				        "event"  => "Updated"
			        ];
		        }

		        case "v2_library_volume": {
			        return [
				        "dam_id"                    => $bible->id,
				        "mark"                      => $bible->copyright,
				        "organization"              => $bible->organization,
                        "fcbh_id"                   => $bible->fcbh->equivalent_id,
			            "status"                    => "live", // for the moment these default to Live
                        "dbp_agreement"             => 1, // for the moment these default to True
                        "expiration"                => "0000-00-00",
				        "language_code"             => $bible->iso,
				        "language_name"             => $bible->language->autonym,
				        "language_english"          => $bible->language->name,
				        "language_iso"              => $bible->iso,
				        "language_iso_2B"           => $bible->language->iso2B,
				        "language_iso_2T"           => $bible->language->iso2T,
				        "language_iso_1"            => $bible->language->iso1,
				        "language_iso_name"         => $bible->language->name, // This is just a duplicate of language_english
				        "language_family_code"      => ($bible->language->parent) ? $bible->language->parent->iso : $bible->language->iso,
				        "language_family_name"      => ($bible->language->parent) ? $bible->language->parent->autonym : $bible->language->autonym,
				        "language_family_english"   => ($bible->language->parent) ? $bible->language->parent->name : $bible->language->name,
				        "language_family_iso"       => $bible->iso,
				        "language_family_iso_2B"    => ($bible->language->parent) ? $bible->language->parent->iso2B : $bible->language->iso2B,
				        "language_family_iso_2T"    => ($bible->language->parent) ? $bible->language->parent->iso2T : $bible->language->iso2T,
				        "language_family_iso_1"     => ($bible->language->parent) ? $bible->language->parent->iso1 : $bible->language->iso1,
				        "version_code"              => substr($bible->id,3),
				        "version_name"              => $bible->translations->where("vernacular",1)->first()->name,
				        "version_english"           => $bible->translations->where("iso","eng")->first()->name,
				        "collection_code"           => $bible->scope,
				        "rich"                      => 0,
				        "collection_name"           => "",
				        "updated_on"                => $bible->updated_at->timestamp,
				        "created_on"                => $bible->created_at->timestamp,
				        "right_to_left"             => $bible->alphabet->direction,
				        "num_art"                   => 0,
				        "num_sample_audio"          => 0,
				        "sku"                       => "",
				        "audio_zip_path"            => null,
				        "font"                      => null,
				        "arclight_language_id"      => "",
				        "media"                     => ["text"],
				        "media_type"                => null,
				        "delivery"                  => ["mobile","web"],
				        "resolution"                => []
			        ];
		        }

		        case "v2_library_metadata": {
					return [
						"dam_id" => $bible->id,
                        "mark" => $bible->copyright,
                        "volume_summary" => null,
                        "font_copyright" => null,
                        "font_url" => (isset($bible->alphabet->primaryFont)) ? $bible->alphabet->primaryFont->fontFileName : null,
                        "organization" => $bible->organizations
					];
		        }

	        }


    }


	public function transformForV4($bible)
	{
		$translations = $bible->translations()->get()->keyBy('iso');

		// Algolia
		return [
			"abbr"          => $bible->id,
			"mark"          => $bible->copyright,
			"name"          => $translations["eng"]->name,
			"vname"         => $translations[$bible->language->iso]->name,
			"organization"  => $bible->organization,
			"language"      => $bible->language->name,
			"date"          => intval($bible->date),
			"country"       => $bible->language->primaryCountry->name ?? '',
		];
	}

	public function transformForDataTables($bible)
	{
		$translations = $bible->translations()->get()->keyBy('iso');
		return [
			$bible->id,
			$bible->copyright,
			$translations["eng"]->name,
			$translations[$bible->language->iso]->name
		];
	}


}
