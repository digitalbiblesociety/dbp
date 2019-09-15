<?php

namespace App\Transformers\Factbook;

use League\Fractal\TransformerAbstract;

class RegionsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_region)
    {
        return $country_region;
    }
}
