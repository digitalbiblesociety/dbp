<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryPeople;
use League\Fractal\TransformerAbstract;

class PeopleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_people)
    {
        return $country_people;
    }
}
