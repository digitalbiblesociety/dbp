<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Sunra\PhpSimple\HtmlDomParser;

class GRNController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grn = json_decode(storage_path('data/connections/grn.json'));

    }

    public function sync()
    {
	    set_time_limit ( 0 );
	    ini_set('memory_limit','500M');
		$storage = Storage::disk('data');
	    $pages = json_decode($storage->get('connections/grn.json'), true);

	    foreach($pages as $page) {
	    	if($storage->exists('connections/grn/' . basename($page) . '.json')) { continue; }

		    $dom = HtmlDomParser::file_get_html( $page, false, null, 0);
		    foreach ($dom->find(".common-list div") as $collection) {
				$albums[] = [
					'title'       => $collection->find('h3.title', 0)->plaintext,
					'link'        => $collection->find('a', 0)->href,
					'description' => $collection->find('p', 0)->plaintext
				];
		    }

		    $storage->put('/connections/grn/' . basename($page) . '.json', json_encode($albums, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		    unset($albums);
		    unset($dom);
	    }
    }

}
