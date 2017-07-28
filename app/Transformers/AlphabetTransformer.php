<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Language\Alphabet;
class AlphabetTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
	public function transform(Alphabet $alphabet)
	{
		$iso = $_GET['iso'] ?? null;
		if(isset($_GET['table'])) return $this->transformForDataTables($alphabet);
		return [
			'name' => $alphabet->name,
			'script' => $alphabet->script,
			'family' => $alphabet->family,
			'type' => $alphabet->type,
			'direction' => $alphabet->direction
		];
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
}
