<?php

namespace App\Transformers\Factbook;

use App\Transformers\BaseTransformer;

class CommunicationsTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($communications)
    {
        return $communications->toArray() ?? [];
    }
}
