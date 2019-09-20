<?php

namespace App\Transformers\Factbook;

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
