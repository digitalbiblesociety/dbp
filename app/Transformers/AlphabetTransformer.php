<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Language\Alphabet;
class AlphabetTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = $_GET['v'] ?? 4;
		$this->iso = $_GET['iso'] ?? "eng";
		$this->continent = $_GET['continent'] ?? false;
	}

    /**
     * A Fractal transformer.
     *
     * @return array
     */
	public function transform(Alphabet $alphabet)
	{
		switch ($this->version) {
			case "jQueryDataTable": return $this->transformForDataTables($alphabet);
			case "2": return $this->transformForV2($alphabet);
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
			'name' => $alphabet->name,
			'script' => $alphabet->script,
			'family' => $alphabet->family,
			'type' => $alphabet->type,
			'direction' => $alphabet->direction
		];
	}

	public function transformForV4(Alphabet $alphabet)
	{
		return [
			'name'      => $alphabet->name,
			'script'    => $alphabet->script,
			'family'    => $alphabet->family,
			'type'      => $alphabet->type,
			'direction' => $alphabet->direction,
			'fonts'     => $alphabet->fonts,
			'languages' => $alphabet->languages
		];
	}

}
