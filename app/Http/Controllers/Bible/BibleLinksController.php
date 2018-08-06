<?php

namespace App\Http\Controllers\Bible;

use App\Transformers\BibleLinksTransformer;
use Illuminate\Http\Request;
use App\Models\Bible\BibleLink;
use App\Models\Organization\Organization;
use App\Http\Controllers\APIController;

class BibleLinksController extends APIController
{
    public function index()
    {
	    $iso             = checkParam('iso', null, 'optional');
	    $limit           = checkParam('limit', null, 'optional') ?? 25;
	    $organization    = checkParam('organization_id', null, 'optional');

	    if(isset($organization)) {
		    $organization = Organization::where('id',$organization)->orWhere('slug',$organization)->first();
		    if(!$organization) return $this->setStatusCode(404)->replyWithError("organization not found");
	    }

	    $bibleLinks = BibleLink::with('bible.currentTranslation')->when($iso, function ($q) use ($iso) {
		    $q->where('iso', $iso);
	    })->when($organization, function ($q) use ($organization) {
		    $q->where('organization_id', $organization->id);
	    })->where('visible',1)->get();

	    return $this->reply(fractal($bibleLinks, new BibleLinksTransformer()));
    }
}
