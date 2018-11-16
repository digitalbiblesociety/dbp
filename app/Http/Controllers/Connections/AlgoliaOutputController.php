<?php

namespace App\Http\Controllers\Connections;

use App\Models\Bible\Bible;
use App\Models\Language\Language;
use App\Transformers\AlgoliaTransformer;
use App\Http\Controllers\APIController;

class AlgoliaOutputController extends APIController
{
    public function bibles()
    {
        $bibles = Bible::with(['filesets','translations','language','country','links'])->withCount(['links','filesets'])->get();
        return $this->reply(fractal($bibles, new AlgoliaTransformer()));
    }

    public function languages()
    {
        if (config('app.env') == 'local') {
            ini_set('memory_limit', '864M');
        }
        $languages = Language::with(['classifications', 'translations', 'countries', 'region'])->get();
        return $this->reply(fractal($languages, new AlgoliaTransformer()));
    }
}
