<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryEthnicity;
use League\Fractal\TransformerAbstract;

class EthnicitiesTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_ethnicity)
    {
        return $country_ethnicity;
    }
}
