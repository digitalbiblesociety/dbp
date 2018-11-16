<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class JoshuaProjectTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $joshuaProject
     *
     * @return array
     */
    public function transform($joshuaProject)
    {
        return [
            'country_id'                => $joshuaProject['country_id'],
            'language_official_iso'     => $joshuaProject['language_official_iso'],
            'language_official_name'    => $joshuaProject['language_official_name'],
            'population'                => (int) $joshuaProject['population'],
            'population_unreached'      => (int) $joshuaProject['population_unreached'],
            'people_groups'             => (int) $joshuaProject['people_groups'],
            'people_groups_unreached'   => (int) $joshuaProject['people_groups_unreached'],
            'joshua_project_scale'      => (int) $joshuaProject['joshua_project_scale'],
            'primary_religion'          => $joshuaProject['primary_religion'],
            'percent_christian'         => (float) $joshuaProject['percent_christian'],
            'resistant_belt'            => (boolean) $joshuaProject['resistant_belt'],
            'percent_literate'          => (float) $joshuaProject['percent_literate'],
        ];
    }
}
