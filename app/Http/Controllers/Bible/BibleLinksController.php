<?php

namespace App\Http\Controllers\Bible;

use App\Models\Language\Language;
use App\Transformers\BibleLinksTransformer;
use App\Models\Organization\Organization;
use App\Http\Controllers\APIController;

class BibleLinksController extends APIController
{
    public function index()
    {
        $iso             = checkParam('iso') ?? 'eng';
        $limit           = checkParam('limit');
        $organization    = checkParam('organization_id');
        $type            = checkParam('type');
        $bible_id        = checkParam('bible_id');

        $cache_string = 'bible_links'.$iso.$limit.$organization.$type.$bible_id;
        $bibleLinks = \Cache::remember($cache_string, 2400, function () use ($iso,$limit,$organization,$type,$bible_id) {
            if ($organization !== null) {
                $organization = Organization::where('id', $organization)->orWhere('slug', $organization)->select('id')->first();
                if (!$organization) {
                    return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404'));
                }
            }
            $language = Language::where('iso', $iso)->select('id')->first();
            if (!$language) {
                return $this->setStatusCode(404)->replyWithError(trans('api.languages_errors_404'));
            }
            $bibleLinks = \DB::table(config('database.connections.dbp.database').'.bible_links')
                             ->join(config('database.connections.dbp.database').'.bible_translations', function ($q) use ($language) {
                                 $q->on('bible_links.bible_id', 'bible_translations.bible_id')->where('language_id', $language->id);
                             })
                             ->when($type, function ($q) use ($type) {
                                 $q->where('bible_links.type', $type);
                             })
                             ->when($organization, function ($q) use ($organization) {
                                 $q->where('bible_links.organization_id', $organization->id);
                             })->when($limit, function ($q) use ($limit) {
                                 $q->limit($limit);
                             })->when($bible_id, function ($q) use ($bible_id) {
                                 $q->where(config('database.connections.dbp.database').'.bible_links.bible_id', $bible_id);
                             })->where('visible', 1)->get();
            return fractal($bibleLinks, new BibleLinksTransformer());
        });

        return $this->reply($bibleLinks);
    }
}
