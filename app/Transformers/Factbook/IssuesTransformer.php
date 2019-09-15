<?php

namespace App\Transformers\Factbook;

use League\Fractal\TransformerAbstract;

class IssuesTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_issues)
    {
        return $country_issues;
    }
}
