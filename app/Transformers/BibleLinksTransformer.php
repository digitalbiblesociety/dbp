<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class BibleLinksTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $bible_link
     *
     * @return array
     */
    public function transform($bible_link)
    {
        return [
            'bible_id'        => $bible_link->bible_id,
            'type'            => $bible_link->type,
            'title'           => $bible_link->title,
            'url'             => $bible_link->url,
            'provider'        => $bible_link->provider,
            'organization_id' => $bible_link->organization_id,
            'name'            => $bible_link->name
        ];
    }
}
