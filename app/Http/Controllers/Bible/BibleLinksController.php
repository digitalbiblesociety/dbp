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
	    $iso             = checkParam('iso', null, 'optional') ?? 'eng';
	    $limit           = checkParam('limit', null, 'optional');
	    $organization    = checkParam('organization_id', null, 'optional');
	    $type            = checkParam('type', null, 'optional');
	    $bible_id        = checkParam('bible_id', null, 'optional');

	    if($organization !== null) {
		    $organization = Organization::where('id',$organization)->orWhere('slug',$organization)->first();
		    if(!$organization) return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404'));
	    }
	    $language = Language::where('iso',$iso)->first();
	    if(!$language) return $this->setStatusCode(404)->replyWithError(trans('api.languages_errors_404'));
	    $bibleLinks = \DB::table(env('DBP_DATABASE').'.bible_links')
		->join(env('DBP_DATABASE').'.bible_translations', function($q) use($language) {
			$q->on('bible_links.bible_id','bible_translations.bible_id')->where('language_id',$language->id);
		})
		->when($type, function ($q) use ($type) {
		    $q->where('bible_links.type', $type);
		})
		->when($organization, function ($q) use ($organization) {
		    $q->where('bible_links.organization_id', $organization->id);
	    })->when($limit, function ($q) use ($limit) {
		    $q->limit($limit);
	    })->when($bible_id, function ($q) use ($bible_id) {
		    $q->where(env('DBP_DATABASE').'.bible_links.bible_id',$bible_id);
	    })->where('visible',1)->get();

	    return $this->reply(fractal($bibleLinks, new BibleLinksTransformer()));
    }
}
