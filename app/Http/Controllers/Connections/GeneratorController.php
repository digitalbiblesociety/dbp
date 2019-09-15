<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;
use App\Models\Bible\Bible;
use App\Traits\AccessControlAPI;

class GeneratorController extends APIController
{
    use AccessControlAPI;

    public function __construct()
    {
        if (config('app.env') != 'local') {
            return $this->replyWithError('this can only be run locally');
        }
    }

    public function bibles()
    {
        return Bible::with('language', 'alphabet', 'translations', 'filesets', 'links', 'country')->whereHas('filesets', function ($q) {
            $q->where('asset_id', 'dbs-web');
        })->get();
    }
}
