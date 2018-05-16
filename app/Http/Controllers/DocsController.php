<?php

namespace App\Http\Controllers;

use App\Models\User\User;
class DocsController extends APIController
{
	/**
	 * Just Docs Routing, nothing to see here.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view('docs.routes.index');
	}

	public function getting_started()
	{
		return view('docs.getting_started');
	}

	public function swagger_v2()
	{
		return view('docs.swagger_v2');
	}

	public function swagger_v4()
	{
		return view('docs.swagger_v4');
	}

	public function swagger_docs_reDoc()
	{
		return view('docs.swagger_docs_reDoc');
	}

	public function swagger_docs_ui()
	{
		return view('docs.swagger_docs');
	}

	public function swagger_docs_gen()
	{
		$swagger = \Swagger\scan(app_path());
		return response()->json($swagger, $this->getStatusCode(), array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'application/json');
	}

	public function swagger_database()
	{
		$docs = json_decode(file_get_contents(public_path('/swagger_database.json')),true);
		return view('docs.swagger_database',compact('docs'));
	}

	public function swagger_database_model($id)
	{
		$docs = json_decode(file_get_contents(public_path('/swagger_database.json')),true);
		if(!isset($docs['components']['schemas'][$id]['properties'])) return $this->setStatusCode(404)->replyWithError("Missing Model");
		return view('docs.swagger_database',compact('docs','id'));
	}

	public function sdk()
	{
		return view('docs.sdk');
	}

	public function history()
	{
		return view('docs.history');
	}

	/**
	 * Move along
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function bibles()
	{
		return view('docs.routes.bibles');
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

	public function bookOrderListing()
	{
		return view('docs.v2.books.bookOrderListing');
	}


}
