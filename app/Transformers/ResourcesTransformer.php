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

		switch($this->route) {
			case "v4_resources.index": {
				return [
					'id'                => intval($resource->id),
					'iso'               => $resource->iso,
					'language'          => @$resource->language->name,
					'cover'             => $resource->cover,
					'cover_thumbnail'   => $resource->cover_thumbnail,
					'translations'      => $resource->translations->where('tag',0)->map(function ($item, $key) {
						$translation[$item['iso']]['title'] = $item['title'];
						$translation[$item['iso']]['description'] = $item['description'];
						return $translation;
					}),
					'tags'              => $resource->translations->where('tag',1),
					'organization'      => [
						'slug'              => $resource->organization->slug,
						'primaryColor'      => $resource->organization->primaryColor,
						'secondaryColor'    => $resource->organization->secondaryColor,
						'translations'      => $resource->organization->translations->pluck('name','language_iso'),
					],
					'links' => $resource->links
				];
			}
			case "v4_resources.show": {
				return [
					'id'                => intval($resource->id),
					'iso'               => $resource->iso,
					'language'          => @$resource->language->name,
					'cover_thumbnail'   => $resource->cover_thumbnail,
					'translations'      => $resource->translations->where('tag',0)->map(function ($item, $key) {
						$translation[$item['iso']]['title'] = $item['title'];
						$translation[$item['iso']]['description'] = $item['description'];
						return $translation;
					}),
					'links' => $resource->links
				];
			}
		}


	}
}
