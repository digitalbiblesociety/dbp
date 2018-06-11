<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class AccessGroupTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($access_group)
    {
        return [
	        'id'          => $access_group->id,
            'name'        => $access_group->name,
            'description' => $access_group->description,
	        'filesets'    => $access_group->filesets->pluck('hash_id'),
	        'keys'        => $access_group->filesets->pluck('key_id'),
	        'types'       => $access_group->types->mapToGroups(function ($item, $key) {
		        return [$item->name => [
			        'country_id'   => $item->country_id,
			        'continent_id' => $item->continent_id,
			        'allowed'      => $item->allowed
			    ]];
	        })
        ];
    }
}
