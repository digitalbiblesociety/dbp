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
        $orgs = Organization::with('resources', 'translations', 'relationships')->get();
        foreach ($orgs as $key => $org) {
            $bibles = [];

            $bible_ids = $org->bibleLinks->pluck('bible_id');
            $bible_ids = collect($bible_ids)->merge($org->bibles->pluck('id'))->unique();

            $orgs[$key]->publishedBibles = Bible::with('translations')->whereIn('id', $bible_ids)->get();
            unset($orgs[$key]->bibleLinks);
            unset($orgs[$key]->bibles);
        }

        return $orgs;
    }

    public function bibles()
    {
        return Bible::with('language','alphabet','translations','filesets','links')->get();
    }

    public function languages()
    {
        return Language::withCount('bibles', 'resources')->with('bibles.translations','primaryCountry','resources.translations')->get();
    }

    public function countries()
    {
        return Country::with(['translations','joshuaProject','geography','languages' => function($query){
            $query->withCount('bibles');
            $query->withCount('resources');
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

    public function libraries()
    {
        //return Library::with('translations','links', 'organization')->get();
    }

}
