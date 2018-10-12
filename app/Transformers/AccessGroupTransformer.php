<?php

namespace App\Transformers;

use App\Models\User\AccessGroup;
use League\Fractal\TransformerAbstract;

class AccessGroupTransformer extends TransformerAbstract
{
	/**
	 * A Fractal transformer.
	 *
	 * @param AccessGroup $access_group
	 *
	 * @return array
	 */
    public function transform(AccessGroup $access_group)
    {
        return [
	        'id'                     => $access_group->id,
            'name'                   => $access_group->name,
	        'current_key_has_access' => (boolean) $access_group->keys->where('key_id',$access_group->current_key)->first(),
            'description'            => $access_group->description,
	        'filesets'               => $access_group->filesets->pluck('hash_id'),
	        'types'                  => $access_group->types->mapToGroups(function ($item, $key) {
		        return [$item->name => [
			        'country_id'   => $item->country_id,
			        'continent_id' => $item->continent_id,
			        'allowed'      => $item->allowed
			    ]];
	        })
        ];
    }
}
