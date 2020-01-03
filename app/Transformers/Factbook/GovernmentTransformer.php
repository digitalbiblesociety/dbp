<?php

namespace App\Transformers\Factbook;

use App\Transformers\BaseTransformer;

class GovernmentTransformer extends BaseTransformer
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
