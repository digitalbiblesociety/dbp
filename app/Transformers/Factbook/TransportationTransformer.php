<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryReligions;
use League\Fractal\TransformerAbstract;

class TransportationTransformer extends TransformerAbstract
{
	/**
	 * A Fractal transformer.
	 *
	 * @return array
	 */
	public function transform($country_transportation)
	{
		return $country_transportation;
	}
}
