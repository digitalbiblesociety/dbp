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
			case "v4_alphabets.one": {
				return $alphabet->toArray();
				break;
			}

		}
	}

}
