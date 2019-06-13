<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;
use App\Models\Bible\Bible;
use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Resource\Resource;
use App\Traits\AccessControlAPI;
class GeneratorController extends APIController
{
    use AccessControlAPI;

    public function __construct() {
        if(config('app.env') != 'local') {
            return $this->replyWithError('this can only be run locally');
        }

        set_time_limit(-1);
        ini_set('memory_limit','6000M');
    }

    public function stats()
    {
        return [
            'bible_count' => Bible::count(),
            'resource_count' => Resource::count(),
            'organization_count' => Organization::count()
        ];
    }

    public function organizations()
    {
        return Organization::with('bibles.translations','resources','translations','logos','relationships')->get();
    }

    public function bibles()
    {
        return Bible::with('language','alphabet','translations','filesets','links','country')->get();
    }

    public function languages()
    {
        return Language::with('bibles.translations','primaryCountry','resources.translations')->get();
    }

    public function countries()
    {
        return Country::with(['translations','languages' => function($query){
            $query->withCount('bibles');
        }])->get();
    }

    public function alphabets()
    {
        return Alphabet::with('fonts', 'languages', 'bibles.currentTranslation')->get();
    }

    public function resources()
    {
        return Resource::with('translations','links', 'organization')->get();
    }

}
