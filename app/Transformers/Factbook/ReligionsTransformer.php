<?php

namespace App\Transformers\Factbook;

use League\Fractal\TransformerAbstract;

class ReligionsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_religions)
    {
        return $country_religions;
    }
}
