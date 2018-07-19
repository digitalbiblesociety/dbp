<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryGovernment;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class GovernmentTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_government)
    {
	    if($country_government == null) return [];
        return $country_government->toArray();
    }
}
