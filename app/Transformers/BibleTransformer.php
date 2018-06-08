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
    public function transform($bible)
    {
	    switch ($this->version) {
		    case "2": return $this->transformForV2($bible);
		    case "3": return $this->transformForV2($bible);
		    case "4": return $this->transformForV4($bible);
		    default:  return $this->transformForV4($bible);
	    }
    }

    public function transformForV2($bible)
    {
    	// Compute v2 ID
	    $v2id = $bible->bible->first()->iso.substr($bible->id,3,3);
	    $v2id .= ($bible->set_size_code[0] == "N") ? "N" : "O";
	    $v2id .= (strpos($bible->set_type_code, 'drama') !== false) ? 2 : 1;
	    $v2id .= (strpos($bible->set_type_code, 'text') !== false) ? "ET" : "DA";
	    $v2id = strtoupper($v2id);

    	    switch($this->route) {

		        case "v2_library_metadata": {
		        	// ISO . 3 Letter . TESTAMENT CODE . DRAMATIZED
		        	$output = [
				        "dam_id"         => $v2id,
				        "fileset_id"     => $bible->id,
				        "mark"           => $bible->copyright->copyright,
				        "volume_summary" => $bible->copyright->copyright_description,
				        "font_copyright" => null,
				        "font_url"       => null
			        ];
			        $organization = @$bible->copyright->organizations->first();
		        	if($organization) {
				        $output["organization"] = [
					        'organization_id'       => $organization->id,
					        'organization'          => $organization->name,
					        'organization_english'  => $organization->name,
					        'organization_role'     => $bible->copyright->role->roleTitle->name,
					        'organization_url'      => $organization->url_website,
					        'organization_donation' => $organization->url_donate,
					        'organization_address'  => $organization->address,
					        'organization_address2' => $organization->address2,
					        'organization_city'     => $organization->city,
					        'organization_state'    => $organization->state,
					        'organization_country'  => $organization->country,
					        'organization_zip'      => $organization->zip,
					        'organization_phone'    => $organization->phone,
				        ];
			        }
			        return $output;
		        }

		        case "v2_volume_history": {
		        	return [
		        		"dam_id" => $v2id,
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
					        "language_name"             => @$bible->language->autonym ?? @$bible->language->name,
					        "language_english"          => @$bible->language->name ?? "",
					        "language_iso"              => $bible->iso ?? "",
					        "language_iso_2B"           => @$bible->language->iso2B ?? "",
					        "language_iso_2T"           => @$bible->language->iso2T ?? "",
					        "language_iso_1"            => @$bible->language->iso1 ?? "",
					        "language_iso_name"         => @$bible->language->name ?? "",
					        "language_family_code"      => ((@$bible->language->parent) ? strtoupper(@$bible->language->parent->iso) : strtoupper($bible->iso)) ?? "",
					        "language_family_name"      => ((@$bible->language->parent) ? @$bible->language->parent->autonym : @$bible->language->name) ?? "",
					        "language_family_english"   => ((@$bible->language->parent) ? @$bible->language->parent->name : @$bible->language->name) ?? "",
					        "language_family_iso"       => $bible->iso ?? "",
					        "language_family_iso_2B"    => ((@$bible->language->parent) ? @$bible->language->parent->iso2B : @$bible->language->iso2B) ?? "",
					        "language_family_iso_2T"    => ((@$bible->language->parent) ? @$bible->language->parent->iso2T : @$bible->language->iso2T) ?? "",
					        "language_family_iso_1"     => ((@$bible->language->parent) ? @$bible->language->parent->iso1 : @$bible->language->iso1) ?? "",
					        "version_code"              => substr($bible->id,3) ?? "",
					        "version_name"              => "Wycliffe Bible Translators, Inc.",
					        "version_english"           => @$bible->currentTranslation->name ?? $bible->id,
					        "collection_code"           => ($fileset->name == "Old Testament") ? "OT" : "NT",
					        "rich"                      => "0",
					        "collection_name"           => $fileset->name,
					        "updated_on"                => "".$bible->updated_at->toDateTimeString() ?? "",
					        "created_on"                => "".$bible->created_at->toDateTimeString() ?? "",
					        "right_to_left"             => (isset($bible->alphabet)) ? (($bible->alphabet->direction == "rtl") ? "true" : "false") : "false",
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
				        break;
			        }
		        }
		        default: return [];
	        }


    }

	public function transformForV4($bible)
	{

		switch($this->route) {

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v4_bible.all",
			*	description="The bibles being returned",
			*	title="v4_bible.all",
			*	@OAS\Xml(name="v4_bible.all"),
			*	@OAS\Items(              @OAS\Property(property="abbr",              ref="#/components/schemas/Bible/properties/id"),
			 *              @OAS\Property(property="name",              ref="#/components/schemas/BibleTranslation/properties/name"),
			 *              @OAS\Property(property="vname",             ref="#/components/schemas/BibleTranslation/properties/name"),
			 *              @OAS\Property(property="language",          ref="#/components/schemas/Language/properties/name"),
			 *              @OAS\Property(property="language_autonym",  ref="#/components/schemas/LanguageTranslation/properties/name"),
			 *              @OAS\Property(property="language_altNames", ref="#/components/schemas/LanguageTranslation/properties/name"),
			 *              @OAS\Property(property="iso",               ref="#/components/schemas/Language/properties/iso"),
			 *              @OAS\Property(property="date",              ref="#/components/schemas/Bible/properties/date"),
			 *              @OAS\Property(property="filesets",          ref="#/components/schemas/BibleFileset")
			 *     )
			 *   )
			 * )
			 */
			case "v4_bible.all": {
				$name = $bible->translatedTitles->where('iso','eng')->first();
				$vname = ($bible->iso != 'eng') ? $bible->translatedTitles->where('iso',$bible->iso)->first() : false;

				$output = [
					"abbr"              => $bible->id,
					"name"              => ($name) ? $name->name : null,
					"vname"             => ($vname) ? $vname->name : null,
					"language"          => @$bible->language->name ?? null,
					"autonym"           => @$bible->language->autonym ?? null,
					"iso"               => $bible->iso,
					"date"              => $bible->date,
					"filesets"          => $bible->filesets->mapToGroups(function ($item, $key) {
						return [$item['bucket_id'] => ['id' => $item['id'],'type' => $item->set_type_code, 'size' => $item->set_size_code]];
					})
				];
				if($bible->langauge) if($bible->langauge->relationLoaded('translations')) $output['language_altNames'] = $bible->language->translations->pluck('name');
				return $output;
			}

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v4_bible.one",
			*	description="The bible being returned",
			*	title="v4_bible.one",
			*	@OAS\Xml(name="v4_bible.one"),
			*	@OAS\Items(              @OAS\Property(property="abbr",          ref="#/components/schemas/Bible/properties/id"),
			 *              @OAS\Property(property="alphabet",      ref="#/components/schemas/Alphabet/properties/script"),
			 *              @OAS\Property(property="mark",          ref="#/components/schemas/Bible/properties/copyright"),
			 *              @OAS\Property(property="name",          ref="#/components/schemas/BibleTranslation/properties/name"),
			 *              @OAS\Property(property="description",   ref="#/components/schemas/BibleTranslation/properties/description"),
			 *              @OAS\Property(property="vname",         ref="#/components/schemas/BibleTranslation/properties/name"),
			 *              @OAS\Property(property="vdescription",  ref="#/components/schemas/BibleTranslation/properties/description"),
			 *              @OAS\Property(property="publishers",    ref="#/components/schemas/Organization"),
			 *              @OAS\Property(property="providers",     ref="#/components/schemas/Organization"),
			 *              @OAS\Property(property="language",      ref="#/components/schemas/Language/properties/name"),
			 *              @OAS\Property(property="iso",           ref="#/components/schemas/Language/properties/iso"),
			 *              @OAS\Property(property="date",          ref="#/components/schemas/Bible/properties/date"),
			 *              @OAS\Property(property="country",       ref="#/components/schemas/Country/properties/name"),
			 *              @OAS\Property(property="books",         ref="#/components/schemas/Book/properties/id"),
			 *              @OAS\Property(property="links",         ref="#/components/schemas/BibleLink"),
			 *              @OAS\Property(property="filesets",      ref="#/components/schemas/BibleFileset"),
			 *     )
			 *   )
			 * )
			 */
			case "v4_bible.one": {
				return [
					"abbr"          => $bible->id,
					"alphabet"      => $bible->alphabet,
					"mark"          => $bible->copyright,
					"name"          => @$bible->currentTranslation->name ?? "",
					"description"   => @$bible->currentTranslation->description ?? "",
					"vname"         => @$bible->vernacularTranslation->name ?? "",
					"vdescription"  => @$bible->vernacularTranslation->description ?? "",
					"publishers"    => $bible->organizations->where('pivot.relationship_type','publisher')->all(),
					"providers"     => $bible->organizations->where('pivot.relationship_type','provider')->all(),
					"language"      => @$bible->language->name ?? "",
					"iso"           => $bible->iso,
					"date"          => $bible->date,
					"country"       => $bible->language->primaryCountry->name ?? '',
					"books"         => $bible->books->sortBy('book.protestant_order')->each(function ($book) {
						// convert to integer array
						$chapters = explode(',',$book->chapters);
						foreach ($chapters as $key => $chapter) $chapters[$key] = intval($chapter);
						$book->chapters = $chapters;
						unset($book->book);
						return $book;
					})->values(),
					"links"        => $bible->links,
					"filesets"     => $bible->filesets->mapToGroups(function ($item, $key) {
						return [$item['bucket_id'] => ['id' => $item['id'],'type' => $item->set_type_code, 'size' => $item->set_size_code]];
					})
				];
			}

			default: return [];
		}
	}

	public function transformForDataTables($bible)
	{
		$font = isset($bible->alphabet) ? (($bible->alphabet->requires_font) ? ' class="requires-font '.$bible->alphabet->script.'" data-font="'.@$bible->alphabet->primaryFont->fontFileName.'"' : '') : '';
		return [
			$bible->language->name ?? "",
			'<a href="/bibles/'.$bible->id.'">'. @$bible->currentTranslation->name .'</a>',
			'<span'.$font.'>'.@$bible->vernacularTranslation->name.'</span>' ?? "",
			$bible->organizations->pluck('slug')->implode(','),
			isset($bible->language) ? ((($bible->language->primaryCountry) ? '<a href="/languages/'.$bible->language->iso.'/">'.$bible->language->primaryCountry->name.'</a>' : "")) : "",
			isset($bible->language) ? @$bible->language->primaryCountry->continent : "",
			$bible->date,
			$bible->id,
			$bible->language->iso ?? "zxx",
			$bible->filesets->pluck('set_type_code')
		];
	}


}
