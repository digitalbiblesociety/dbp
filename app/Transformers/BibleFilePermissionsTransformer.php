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
	    /**
	     * @OAS\Response(
	     *   response="v4_bible_filesets_permissions.index",
	     *   description="The permissions for a specific bible fileset",
	     *   @OAS\MediaType(
	     *     mediaType="application/json",
	     *     @OAS\Schema(
	     *          @OAS\Property(property="fileset_id",          ref="#/components/schemas/BibleFileset/properties/hash_id"),
	     *          @OAS\Property(property="access_type",         ref="#/components/schemas/Access/properties/access_granted"),
	     *          @OAS\Property(property="access_granted",      ref="#/components/schemas/Access/properties/access_granted"),
	     *          @OAS\Property(property="granted_at",          ref="#/components/schemas/Access/properties/granted_at"),
	     *          @OAS\Property(property="updated_at",          ref="#/components/schemas/Access/properties/updated_at"),
	     *     )
	     *   )
	     * )
	     */
        return [
	        'fileset_id'     => $access->fileset->id,
	        'access_type'    => $access->access_type,
	        'access_granted' => boolval($access->access_granted),
	        'granted_at'     => $access->created_at->toDateTimeString(),
	        'updated_at'     => $access->updated_at->toDateTimeString(),
        ];
    }
}
