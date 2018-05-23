<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryEconomy;
use League\Fractal\TransformerAbstract;

class EconomyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_economy)
    {
        return $country_economy;
    }
}
