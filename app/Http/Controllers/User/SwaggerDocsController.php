<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OpenApi\Annotations\Parameter;

class SwaggerDocsController extends Controller
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

        define("API_URL_DOCS", config('app.api_url'));
        $swagger = \Cache::remember('OAS_' . $version, now()->addDay(), function () use ($version) {
            $swagger = \OpenApi\scan(app_path());
            $swagger->tags  = $this->swaggerVersionTags($swagger->tags, $version);
            $swagger->paths = $this->swaggerVersionPaths($swagger->paths, $version);
            $swagger->paths = $this->addCommonParameters($swagger->paths);
            $swagger->components  = $this->removeUnusedComponents($swagger, $version);
            return $swagger;
        });
        return response()->json($swagger)->header('Content-Type', 'application/json');
    }

    private function removeUnusedComponents($swagger, $version)
    {
        $schema_regex = '/(?<=schemas\\\\\/)(.*?)(?=\\\\\/|")/m';
        preg_match_all($schema_regex, json_encode($swagger), $matches);
        $schemas_used = array_unique($matches[0]);
        foreach ($swagger->components->schemas as $key => $schema) {
            if (!in_array($schema->schema, $schemas_used)) {
                unset($swagger->components->schemas[$key]);
            }
        }
        return $swagger->components;
    }

    private function swaggerVersionTags($tags, $version)
    {
        foreach ($tags as $key => $tag) {
            if (!Str::startsWith($tags[$key]->description, $version)) {
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
            if (isset($path->get->operationId) && !Str::startsWith($path->get->operationId, $version)) {
                unset($paths[$key]);
            }
            if (isset($path->put->operationId) && !Str::startsWith($path->put->operationId, $version)) {
                unset($paths[$key]);
            }
            if (isset($path->post->operationId) && !Str::startsWith($path->post->operationId, $version)) {
                unset($paths[$key]);
            }
            if (isset($path->delete->operationId) && !Str::startsWith($path->delete->operationId, $version)) {
                unset($paths[$key]);
            }
        }

        return $paths;
    }

    private function addCommonParameters($paths)
    {
        foreach ($paths as $path) {
            if (isset($path->get->operationId)) {
                $path->get->parameters = $this->addCommonParametersToPath($path->get->parameters);
            }
            if (isset($path->post->operationId)) {
                $path->post->parameters = $this->addCommonParametersToPath($path->post->parameters);
            }
            if (isset($path->put->operationId)) {
                $path->put->parameters = $this->addCommonParametersToPath($path->put->parameters);
            }
            if (isset($path->delete->operationId)) {
                $path->delete->parameters  = $this->addCommonParametersToPath($path->delete->parameters);
            }
        }
        return $paths;
    }

    private function addCommonParametersToPath($parameters)
    {
        if (gettype($parameters) == 'string') {
            $parameters = [];
        }
        $parameters[] = new Parameter(['ref' => '#/components/parameters/format']);
        $parameters[] = new Parameter(['ref' => '#/components/parameters/key']);
        $parameters[] = new Parameter(['ref' => '#/components/parameters/pretty']);
        $parameters[] = new Parameter(['ref' => '#/components/parameters/version_number']);
        return $parameters;
    }
}
