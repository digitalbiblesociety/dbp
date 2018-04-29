<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class BibleFilePermissionsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($access)
    {
        return [
        	'hash_id'        => $access->hash_id,
	        'fileset_id'     => $access->fileset->id,
	        //'names'          => $access->fileset->bible->map(function ($bible) { return $bible->translations->pluck('name'); })->flatten(),
	        'whitelist'      => boolval($access->whitelist),
	        'access_granted' => boolval($access->access_granted),
	        'granted_at'     => $access->created_at->toDateTimeString(),
	        'updated_at'     => $access->updated_at->toDateTimeString(),
        ];
    }
}
