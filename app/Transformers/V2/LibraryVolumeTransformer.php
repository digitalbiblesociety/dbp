<?php

namespace App\Transformers\V2;

use App\Models\Bible\BibleFileset;
use App\Transformers\BaseTransformer;

class LibraryVolumeTransformer extends BaseTransformer
{

    public function transform($fileset)
    {
	    switch($this->route) {

            case "v2_volume_history": {
                return [
                    "dam_id" => $fileset->v2_id,
                    "time"   => $fileset->updated_at->toDateTimeString(),
                    "event"  => "Updated"
                ];
                break;
            }

		    case 'v2_library_volume': {
			    /**
			     * @OA\Schema (
			     *	type="array",
			     *	schema="v2_library_volume",
			     *	description="",
			     *	title="v2_library_volume",
			     *	@OA\Xml(name="v2_library_volume"),
			     *	@OA\Items(
			     *      @OA\Property(property="dam_id",                  ref="#/components/schemas/BibleFileset/properties/id"),
			     *      @OA\Property(property="fcbh_id",                 ref="#/components/schemas/BibleFileset/properties/id"),
			     *      @OA\Property(property="volume_name",             ref="#/components/schemas/BibleTranslation/properties/name"),
			     *      @OA\Property(property="status",                  @OA\Schema(type="string",example="live")),
			     *      @OA\Property(property="dbp_agreement",           @OA\Schema(type="string",example="true")),
			     *      @OA\Property(property="expiration",              @OA\Schema(type="string",example="0000-00-00")),
			     *      @OA\Property(property="language_code",           ref="#/components/schemas/Language/properties/iso"),
			     *      @OA\Property(property="language_name",           ref="#/components/schemas/LanguageTranslation/properties/name"),
			     *      @OA\Property(property="language_english",        ref="#/components/schemas/Language/properties/name"),
			     *      @OA\Property(property="language_iso",            ref="#/components/schemas/Language/properties/iso"),
			     *      @OA\Property(property="language_iso_2B",         ref="#/components/schemas/Language/properties/iso2B"),
			     *      @OA\Property(property="language_iso_2T",         ref="#/components/schemas/Language/properties/iso2T"),
			     *      @OA\Property(property="language_iso_1",          ref="#/components/schemas/Language/properties/iso1"),
			     *      @OA\Property(property="language_iso_name",       ref="#/components/schemas/Language/properties/name"),
			     *      @OA\Property(property="language_family_code",    ref="#/components/schemas/Language/properties/iso"),
			     *      @OA\Property(property="language_family_name",    ref="#/components/schemas/LanguageTranslation/properties/name"),
			     *      @OA\Property(property="language_family_english", ref="#/components/schemas/Language/properties/name"),
			     *      @OA\Property(property="language_family_iso",     ref="#/components/schemas/Language/properties/iso"),
			     *      @OA\Property(property="language_family_iso_2B",  ref="#/components/schemas/Language/properties/iso2B"),
			     *      @OA\Property(property="language_family_iso_2T",  ref="#/components/schemas/Language/properties/iso2T"),
			     *      @OA\Property(property="language_family_iso_1",   ref="#/components/schemas/Language/properties/iso1"),
			     *      @OA\Property(property="version_code",            ref="#/components/schemas/BibleFileset/properties/id"),
			     *      @OA\Property(property="version_name",            ref="#/components/schemas/BibleTranslation/properties/name"),
			     *      @OA\Property(property="version_english",         ref="#/components/schemas/BibleTranslation/properties/name"),
			     *      @OA\Property(property="collection_code",         @OA\Schema(type="string", example="NT",enum={"OT", "NT"})),
			     *      @OA\Property(property="rich",                    @OA\Schema(type="integer",example=1,enum={1, 0})),
			     *      @OA\Property(property="collection_name",         @OA\Schema(type="string",example="New Testament",enum={"Old Testament", "New Testament"})),
			     *      @OA\Property(property="updated_on",              ref="#/components/schemas/BibleFileset/properties/updated_at"),
			     *      @OA\Property(property="created_on",              ref="#/components/schemas/BibleFileset/properties/created_at"),
			     *      @OA\Property(property="right_to_left",           @OA\Schema(type="string", example="rtl",enum={"rtl", "ltr"})),
			     *      @OA\Property(property="num_art",                 @OA\Schema(type="integer",example=0)),
			     *      @OA\Property(property="num_sample_audio",        @OA\Schema(type="integer",example=0)),
			     *      @OA\Property(property="sku",                     ref="#/components/schemas/BibleEquivalent/properties/equivalent_id"),
			     *      @OA\Property(property="audio_zip_path",          @OA\Schema(type="string")),
			     *      @OA\Property(property="font",                    ref="#/components/schemas/AlphabetFont"),
			     *      @OA\Property(property="arclight_language_id",    ref="#/components/schemas/LanguageCode/properties/code"),
			     *      @OA\Property(property="media",                   @OA\Schema(type="string",example="Audio",enum={"Audio", "Text"})),
			     *      @OA\Property(property="media_type",              @OA\Schema(type="string",example="Drama",enum={"Drama", "Non-Drama"})),
			     *      @OA\Property(property="delivery",                @OA\Schema(type="string")),
			     *      @OA\Property(property="resolution",              @OA\Schema(type="array"))
			     *     )
			     *   )
			     * )
			     */
			    return [
				    'dam_id'                    => $fileset->generated_id,
				    'fcbh_id'                   => $fileset->generated_id,
				    'volume_name'               => (string) $fileset->volume_name,
				    'status'                    => 'live', // for the moment these default to Live
				    'dbp_agreement'             => 'true', // for the moment these default to True
				    'expiration'                => '0000-00-00',
				    'language_code'             => strtoupper($fileset->iso),
				    'language_name'             => (string) $fileset->autonym,
				    'language_english'          => (string) $fileset->language_name,
				    'language_iso'              => (string) $fileset->iso,
				    'language_iso_2B'           => (string) $fileset->iso2B,
				    'language_iso_2T'           => (string) $fileset->iso2T,
				    'language_iso_1'            => (string) $fileset->iso1,
				    'language_iso_name'         => (string) $fileset->language_name,
				    'language_family_code'      => (string) $fileset->iso,
				    'language_family_name'      => (string) $fileset->language_name,
				    'language_family_english'   => (string) $fileset->language_name,
				    'language_family_iso'       => (string) $fileset->iso,
				    'language_family_iso_2B'    => (string) $fileset->iso2B,
				    'language_family_iso_2T'    => (string) $fileset->iso2T,
				    'language_family_iso_1'     => (string) $fileset->iso1,
				    'version_code'              => substr($fileset->bible_id,3),
				    'version_name'              => $fileset->version_name,
				    'version_english'           => $fileset->version_name,
				    'collection_code'           => (substr($fileset->generated_id,-4,1) == 'N') ? 'NT' : 'OT',
				    'rich'                      => (string) ($fileset->set_type_code == 'text_format') ? '1' : '0',
				    'collection_name'           => (substr($fileset->generated_id,-4,1) == 'N') ? 'New Testament' : 'Old Testament',
				    'updated_on'                => (string) $fileset->updated_at,
				    'created_on'                => (string) $fileset->created_at,
				    'right_to_left'             => isset($fileset->alphabet) ? (($fileset->direction == 'rtl') ? 'true' : 'false') : 'false',
				    'num_art'                   => '0',
				    'num_sample_audio'          => '0',
				    'sku'                       => '',
				    'audio_zip_path'            => $fileset->generated_id.'/'.$fileset->generated_id.'.zip',
				    'font'                      => null,
				    'arclight_language_id'      => '',
				    'media'                     => (strpos($fileset->set_type_code, 'audio') !== false) ? 'audio' : 'text',
				    'media_type'                => ($fileset->set_type_code == 'audio_drama') ? 'Drama' : 'Non-Drama',
				    'delivery'                  => [
					    'mobile',
					    'web',
					    'local_bundled',
					    'subsplash'
				    ],
				    'resolution'                => []
			    ];
			    break;
		    }
		    default: return [];
	    }
    }
}
