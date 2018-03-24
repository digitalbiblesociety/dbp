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
			case "jQueryDataTable": return $this->transformForDataTables($resource);
			case "4": return $this->transformForV4($resource);
			default:  return $this->transformForV4($resource);
		}
	}

	public function transformForV4($resource) {
		return [
			'id'                => intval($resource->id),
			'iso'               => $resource->iso,
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

	public function transformForDataTables(Resource $resource)
	{
		$translation = $resource->translations->where('tag',0)->where('iso',\i18n::getCurrentLocale())->first();
		return [
			'<a href="/resources/'.$resource->id.'">'. $translation->title .'</a>',
			$resource->language->name ?? ""
		];
	}
}
