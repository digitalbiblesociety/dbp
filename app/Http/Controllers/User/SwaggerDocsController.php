<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

    public function swaggerDocsGen($version)
    {
        $otherVersion = ($version === 'v4') ? 'v2' : 'v4';

        $swagger = \Cache::remember('OAS_'.$version, now()->addDay(), function () use ($version, $otherVersion) {
            $swagger = \OpenApi\scan(app_path());
            foreach ($swagger->components->schemas as $key => $component) {
                if (Str::contains($swagger->components->schemas[$key]->title, $otherVersion)) {
                    unset($swagger->components->schemas[$key]);
                }
            }
            $swagger->tags  = $this->swaggerVersionTags($swagger->tags, $version);
            $swagger->paths = $this->swaggerVersionPaths($swagger->paths, $version);
            return $swagger;
        });

        return response()->json($swagger)->header('Content-Type', 'application/json');
    }

    private function swaggerVersionTags($tags, $version)
    {
        foreach ($tags as $key => $tag) {
            if (Str::contains($tags[$key]->description, $version)) {
                unset($tags[$key]);
            } else {
                $tags[$key]->description = substr($tags[$key]->description, 2);
            }
        }
        return Arr::flatten($tags);
    }

    private function swaggerVersionPaths($paths, $version)
    {
        foreach ($paths as $key => $path) {
            if (isset($path->get->operationId) && Str::contains($path->get->operationId, $version)) {
                unset($paths[$key]);
            }
            if (isset($path->put->operationId) && Str::contains($path->put->operationId, $version)) {
                unset($paths[$key]);
            }
            if (isset($path->post->operationId) && Str::contains($path->post->operationId, $version)) {
                unset($paths[$key]);
            }
            if (isset($path->delete->operationId) && Str::contains($path->delete->operationId, $version)) {
                unset($paths[$key]);
            }
        }

        return $paths;
    }

}
