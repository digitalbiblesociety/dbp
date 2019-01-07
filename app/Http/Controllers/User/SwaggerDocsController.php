<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;

class SwaggerDocsController extends APIController
{

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

    public function swaggerDocsGen()
    {
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

        $swagger->tags = $this->swaggerVersionTags($swagger->tags, $version);
        $swagger->paths = $this->swaggerVersionPaths($swagger->paths, $version);

        return response()->json($swagger, $this->getStatusCode(), [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                         ->header('Content-Type', 'application/json');
    }

    private function swaggerVersionTags($tags, $version)
    {
        foreach ($tags as $key => $tag) {
            if (strpos($tags[$key]->description, $version) !== 0) {
                unset($tags[$key]);
            } else {
                $tags[$key]->description = substr($tags[$key]->description, 2);
            }
        }
        return array_flatten($tags);
    }

    private function swaggerVersionPaths($paths, $version)
    {
        foreach ($paths as $key => $path) {
            if (isset($path->get->operationId) && (strpos($path->get->operationId, $version) !== 0)) {
                unset($paths[$key]);
            }
            if (isset($path->put->operationId) && (strpos($path->put->operationId, $version) !== 0)) {
                unset($paths[$key]);
            }
            if (isset($path->post->operationId) && (strpos($path->post->operationId, $version) !== 0)) {
                unset($paths[$key]);
            }
            if (isset($path->delete->operationId) && (strpos($path->delete->operationId, $version) !== 0)) {
                unset($paths[$key]);
            }
        }
        return $paths;
    }

}
