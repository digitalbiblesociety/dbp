<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryGovernment;
use League\Fractal\TransformerAbstract;

class GovernmentTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_government)
    {
        return $country_government;
    }
}
