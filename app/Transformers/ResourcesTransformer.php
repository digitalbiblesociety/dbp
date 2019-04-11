<?php

namespace App\Transformers;

use App\Models\Resource\Resource;

class ResourcesTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @param Resource $resource
     *
     * @return array
     */
    public function transform(Resource $resource)
    {
        return $this->transformForV4($resource);
    }

    public function transformForV4($resource)
    {

        $vname = optional($resource->translations->where('tag', 0)->where('vernacular', 1)->first())->title;
        $vname_description = optional($resource->translations->where('tag', 0)->where('vernacular', 1)->first())->description;
        $name = optional($resource->translations->where('tag', 0)->where('iso', 6414)->first())->title;
        $name_description = optional($resource->translations->where('tag', 0)->where('iso', 6414)->first())->description;
        if ($vname === $name) {
            $name = '';
        }
        if ($vname_description === $name_description) {
            $name_description = '';
        }

        switch ($this->route) {
            case 'v4_resources.show':
                return [
                    'id'                 => (int) $resource->id,
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


            case 'v4_resources.index':
            default:
                return [
                    'id'                => (int) $resource->id,
                    'iso'               => $resource->iso,
                    'language'          => $resource->language->name,
                    'vname'             => $vname,
                    'name'              => $name,
                    'links'             => $resource->links,
                    'type'              => $resource->type,
                    'organization'      => $resource->organization->translations->where('language_iso', $this->i10n)->first()->name ?? str_replace('-', ' ', $resource->organization->slug)
                ];
        }
    }
}
