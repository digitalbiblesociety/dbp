<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\User;
use database\seeds\SeederHelper;


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

	public function start()
	{
		return view('docs.guide.start');
	}

	public function swagger_v2()
	{
		return view('docs.swagger_v2');
	}

	public function swagger_v4()
	{
		return view('docs.swagger_v4');
	}

	public function code_analysis()
	{
		$csv_helper = new SeederHelper();
		$analysis = $csv_helper->csv_to_array(storage_path('app/code_analysis.csv'));
		$analysis = $analysis[0];

		return view('docs.code_analysis',compact('analysis'));
	}

	public function swagger_docs_gen()
	{
		ini_set('memory_limit','250M');
		$version      = checkParam('v', null, 'optional') ?? "v4";
		$otherVersion = ($version == "v4") ? "v2" : "v4";
		$swagger      = \OpenApi\scan(app_path());


		foreach ($swagger->components->schemas as $key => $component) {
			if (substr($swagger->components->schemas[$key]->title, 0, 2) == $otherVersion) {
				unset($swagger->components->schemas[$key]);
			}
		}

		foreach ($swagger->components->responses as $key => $response) {
			unset($swagger->components->responses[$key]);
		}

		foreach ($swagger->tags as $key => $tag) {
			if (substr($swagger->tags[$key]->description, 0, 2) != $version) {
				unset($swagger->tags[$key]);
			} else {
				$swagger->tags[$key]->description = substr($swagger->tags[$key]->description, 2);
			}
		}
		foreach ($swagger->paths as $key => $path) {


			if (isset($path->get->operationId)) {

/*
				$path->get->summary = trans("api.docs.paths.".$path->get->operationId.'.summary');
				$path->get->description = trans("api.docs.paths.".$path->get->operationId.'.description');
				foreach($path->get->parameters as $key => $parameter) {
					if(isset($parameter->ref)) {
						$path = $parameter->ref;
						$path = str_replace('#/components/','',$path);
						$path = str_replace('/','.',$path);
					}
					$ref = basename($parameter->ref);
					$path->get->parameters[$key]->name =  trans("api.".$path->get->operationId.'.'.$ref.'.description');
					$path->get->parameters[$key]->description =  trans("api.".$path->get->operationId.'.'.$ref.'.description');
				}
				$this->fetchTranslations($path->get->operationId);
*/
				if (substr($path->get->operationId, 0, 2) != $version) {
					unset($swagger->paths[$key]);
				}
			}
			if (isset($path->put->operationId)) {
				if (substr($path->put->operationId, 0, 2) != $version) {
					unset($swagger->paths[$key]);
				}
			}
			if (isset($path->post->operationId)) {
				if (substr($path->post->operationId, 0, 2) != $version) {
					unset($swagger->paths[$key]);
				}
			}
			if (isset($path->delete->operationId)) {
				if (substr($path->delete->operationId, 0, 2) != $version) {
					unset($swagger->paths[$key]);
				}
			}
		}

		return response()->json($swagger, $this->getStatusCode(), [],
			JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type',
			'application/json');
	}

	public function fetchTranslations($operation_id)
	{
		$translation = trans("api." . $operation_id . '.summary');

	}

	public function swagger_database()
	{
		$docs = json_decode(file_get_contents(public_path('/swagger_database.json')), true);

		return view('docs.swagger_database', compact('docs'));
	}

	public function swagger_database_model($id)
	{
		$docs = json_decode(file_get_contents(public_path('/swagger_database.json')), true);
		if (!isset($docs['components']['schemas'][$id]['properties'])) {
			return $this->setStatusCode(404)->replyWithError("Missing Model");
		}

		return view('docs.swagger_database', compact('docs', 'id'));
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
		$teammates = User::whereHas('role.organization', function ($q) {
			$q->where('role', 'teammember');
		})->get();

		return view('docs.team', compact('teammates'));
	}

	public function bookOrderListing()
	{
		return view('docs.v2.books.bookOrderListing');
	}


}
