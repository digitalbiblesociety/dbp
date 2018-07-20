<?php

namespace App\Transformers;

use App\Models\Resource\Resource;

class ResourcesTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
	public function transform(Resource $resource)
	{
		switch ($this->version) {
			case "4": return $this->transformForV4($resource);
			default:  return $this->transformForV4($resource);
		}
	}

	public function transformForV4($resource) {

		$vname = @$resource->translations->where('tag',0)->where('iso',$resource->iso)->first()->title ?? '';
		$vname_description = @$resource->translations->where('tag',0)->where('iso',$resource->iso)->first()->description ?? '';
		$name = @$resource->translations->where('tag',0)->where('iso',$this->i10n)->first()->title ?? '';
		$name_description = @$resource->translations->where('tag',0)->where('iso',$this->i10n)->first()->description ?? '';
		if($vname == $name) $name = '';
		if($vname_description == $name_description) $name_description = '';

		switch($this->route) {
			case "v4_resources.index": {
				return [
					'id'                => intval($resource->id),
					'iso'               => $resource->iso,
					'language'          => $resource->language->name,
					'vname'             => $vname,
					'name'              => $name,
					'links'             => $resource->links,
					'type'              => $resource->type,
					'organization'      => $resource->organization->translations->where('language_iso',$this->i10n)->first()->name ?? str_replace('-',' ',$resource->organization->slug)
				];
			}
			case "v4_resources.show": {
				return [
					'id'                 => intval($resource->id),
					'iso'                => $resource->iso,
					'language'           => $resource->language->name,
					'cover_thumbnail'    => $resource->cover_thumbnail,
					'vname'              => $vname,
					'vname_description'  => $vname_description,
					'name'               => $name,
					'name_description'   => $name_description,
					'links'              => $resource->links,
					'organization'       => $resource->organization
				];
			}
		}


	}
}
