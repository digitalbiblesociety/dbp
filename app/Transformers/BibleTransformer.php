<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Bible\Bible;
class BibleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Bible $bible)
    {
        return [
            "abbr" => $bible->abbr,
			"iso"  => $bible->language->iso
        ];
    }
}
