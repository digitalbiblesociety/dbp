<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
class DocsController extends APIController
{

	/**
	 * Keeps Track of the DBP2 rebuild progress
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function progress()
	{
		$progress = collect(json_decode(file_get_contents(public_path('static/progress.json'))));
		$progress->totalcount = 0;
		$progress->omitted_count = 0;
		$progress->uncompleted_count = 0;
		$progress->static_count = 0;
		$progress->supported_count = 0;
		foreach ($progress as $catagory => $items) {

			foreach ($items as $item) {
				if(!isset($item->status)) dd($item);
				switch($item->status) {
					case "supported":   {$progress->supported_count++;break;}
					case "omitted":     {$progress->omitted_count++;break;}
					case "uncompleted": {$progress->uncompleted_count++;break;}
					case "static":      {$progress->static_count++;break;}
				}
				$progress->totalcount++;
			}
		}
		$totalpercentage = (100 / $progress->totalcount);
		$progress->totalcount_percentage = ($progress->totalcount * $totalpercentage);
		$progress->omitted_count_percentage = ($progress->omitted_count * $totalpercentage);
		$progress->uncompleted_count_percentage = ($progress->uncompleted_count * $totalpercentage);
		$progress->static_count_percentage = ($progress->static_count * $totalpercentage);
		$progress->supported_count_percentage = ($progress->supported_count * $totalpercentage);
		return view('docs.progress',compact('progress'));
	}

	/**
	 * Just Docs Routing, nothing to see here.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view('docs.routes.index');
	}

	/**
	 * Move along
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function bibles()
	{
		return view('docs.routes.bibleEquivalents');
	}

	/**
	 * Keep going
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function bibleEquivalents()
	{
		return view('docs.routes.bibleEquivalents');
	}

	/**
	 * No loitering citizen
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function books()
	{
		return view('docs.routes.books');
	}

	public function languages()
	{
		return view('docs.routes.languages');
	}

	public function countries()
	{
		return view('docs.routes.countries');
	}

	public function alphabets()
	{
		return view('docs.routes.alphabets');
	}

	public function team()
	{
		$teammates = User::whereHas('role.organization', function($q) {$q->where('role', 'teammember');})->get();
		return view('docs.team',compact('teammates'));
	}


}
