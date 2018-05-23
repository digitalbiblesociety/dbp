<?php

namespace App\Transformers\Factbook;

use App\Models\Country\FactBook\CountryEnergy;
use League\Fractal\TransformerAbstract;

class EnergyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($country_energy)
    {
        return $country_energy;
    }
}
