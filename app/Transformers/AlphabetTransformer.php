<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Language\Alphabet;
class AlphabetTransformer extends BaseTransformer
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
	public function transform(Alphabet $alphabet)
	{
		switch ($this->version) {
			case "jQueryDataTable": return $this->transformForDataTables($alphabet);
			case "2":
			case "3": return $this->transformForV2($alphabet);
			case "4":
			default: return $this->transformForV4($alphabet);
		}
	}

	/**
	 * Transforms the Response for the data table jquery plugin
	 * Single quotes are preferred for a cleaner escape free json response
	 * @param Alphabet $alphabet
	 *
	 * @return array
	 */
	public function transformForDataTables(Alphabet $alphabet)
	{
			return [
				"<a href='".env('APP_URL')."/alphabets/$alphabet->script'>$alphabet->name</a>",
				$alphabet->script,
				$alphabet->family,
				$alphabet->type,
				$alphabet->direction
			];
	}

	public function transformForV2(Alphabet $alphabet)
	{
		return [
			'name'      => $alphabet->name,
			'script'    => $alphabet->script,
			'family'    => $alphabet->family,
			'type'      => $alphabet->type,
			'direction' => $alphabet->direction
		];
	}

	public function transformForV4(Alphabet $alphabet)
	{
		switch($this->route) {

			/**
			 *
			 * @OAS\Schema (
			 *     type="array",
			 *     schema="v4_alphabets_all_response",
			 *     description="The minimized alphabet return for the all alphabets route",
			 *     title="The all alphabets response",
			 *     @OAS\Xml(name="v4_alphabets_all_response"),
			 *     @OAS\Items(
			 *          @OAS\Property(property="name",      ref="#/components/schemas/Alphabet/properties/name"),
			 *          @OAS\Property(property="script",    ref="#/components/schemas/Alphabet/properties/script"),
			 *          @OAS\Property(property="family",    ref="#/components/schemas/Alphabet/properties/family"),
			 *          @OAS\Property(property="type",      ref="#/components/schemas/Alphabet/properties/type"),
			 *          @OAS\Property(property="direction", ref="#/components/schemas/Alphabet/properties/direction")
			 *     )
			 * )
			 *
			 */
			case "v4_alphabets.all": {
				return [
					'name'      => $alphabet->name,
					'script'    => $alphabet->script,
					'family'    => $alphabet->family,
					'type'      => $alphabet->type,
					'direction' => $alphabet->direction
				];
				break;
			}

			/**
			 *
			 * @OAS\Schema (
			 *     type="object",
			 *     schema="v4_alphabets_one_response",
			 *     description="The full alphabet return for the single alphabet route",
			 *     title="The single alphabet response",
			 *     @OAS\Xml(name="v4_alphabets_one_response"),
			 *     @OAS\Property(property="name",                   ref="#/components/schemas/Alphabet/properties/name"),
			 *     @OAS\Property(property="script",                 ref="#/components/schemas/Alphabet/properties/script"),
			 *     @OAS\Property(property="family",                 ref="#/components/schemas/Alphabet/properties/family"),
			 *     @OAS\Property(property="type",                   ref="#/components/schemas/Alphabet/properties/type"),
			 *     @OAS\Property(property="direction",              ref="#/components/schemas/Alphabet/properties/direction"),
			 *     @OAS\Property(property="fonts",type="array",     @OAS\Items(ref="#/components/schemas/AlphabetFont")),
			 *     @OAS\Property(property="languages",type="array", @OAS\Items(ref="#/components/schemas/Language")),
			 *     @OAS\Property(property="bibles",type="array",    @OAS\Items(ref="#/components/schemas/Bibles"))
			 * )
			 *
			 */
			case "v4_alphabets.one": {
				return $alphabet->toArray();
				break;
			}

		}
	}

}
