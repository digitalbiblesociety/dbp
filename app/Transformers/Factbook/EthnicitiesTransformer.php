<?php

namespace App\Transformers\Factbook;

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
