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

    public function swaggerV2()
    {
        return view('docs.swagger_v2');
    }

    public function swaggerV4()
    {
        return view('docs.swagger_v4');
    }

    public function codeAnalysis()
    {
        $analysis = csvToArray(storage_path('app/code_analysis.csv'));
        $analysis = $analysis[0];

        return view('docs.code_analysis', compact('analysis'));
    }

    public function swaggerDocsGen()
    {
        ini_set('memory_limit', '250M');
        $version      = checkParam('v') ?? 'v4';
        $otherVersion = ($version === 'v4') ? 'v2' : 'v4';
        $swagger      = \OpenApi\scan(app_path());


        foreach ($swagger->components->schemas as $key => $component) {
            if (strpos($swagger->components->schemas[$key]->title, $otherVersion) === 0) {
                unset($swagger->components->schemas[$key]);
            }
        }

        foreach ($swagger->components->responses as $key => $response) {
            unset($swagger->components->responses[$key]);
        }

        foreach ($swagger->tags as $key => $tag) {
            if (strpos($swagger->tags[$key]->description, $version) !== 0) {
                unset($swagger->tags[$key]);
            } else {
                $swagger->tags[$key]->description = substr($swagger->tags[$key]->description, 2);
            }
        }
        $swagger->tags = array_flatten($swagger->tags);

        foreach ($swagger->paths as $key => $path) {
            if (isset($path->get->operationId)) {
                if (strpos($path->get->operationId, $version) !== 0) {
                    unset($swagger->paths[$key]);
                }
            }
            if (isset($path->put->operationId)) {
                if (strpos($path->put->operationId, $version) !== 0) {
                    unset($swagger->paths[$key]);
                }
            }
            if (isset($path->post->operationId)) {
                if (strpos($path->post->operationId, $version) !== 0) {
                    unset($swagger->paths[$key]);
                }
            }
            if (isset($path->delete->operationId)) {
                if (strpos($path->delete->operationId, $version) !== 0) {
                    unset($swagger->paths[$key]);
                }
            }
        }

        return response()->json($swagger, $this->getStatusCode(), [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                         ->header('Content-Type', 'application/json');
    }

    public function swaggerDatabase()
    {
        $docs = json_decode(file_get_contents(public_path('/swagger_database.json')), true);

        return view('docs.swagger_database', compact('docs'));
    }

    public function swaggerDatabaseModel($id)
    {
        $docs = json_decode(file_get_contents(public_path('/swagger_database.json')), true);
        if (!isset($docs['components']['schemas'][$id]['properties'])) {
            return $this->setStatusCode(404)->replyWithError('Missing Model');
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
