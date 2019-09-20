<?php

namespace App\Transformers\Factbook;

use League\Fractal\TransformerAbstract;

class GeographyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_geography)
    {
        return $country_geography;
    }
}
