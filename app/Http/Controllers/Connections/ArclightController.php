<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;

class ArclightController extends APIController
{

    protected $api_key;
    protected $base_url;

    /**
     * ArclightController constructor.
     */
    public function __construct()
    {
    	parent::__construct();
        $this->api_key  = env('ARCLIGHT_API');
        $this->base_url = 'https://api.arclight.org/v2/';
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @param string $iso
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index(string $iso)
    {
        // Fetch Language Id equivalent
        $languages   = $this->fetchLocal('languages.json');
        $language_id = $languages[$iso];
        if (!$language_id) return $this->setStatusCode(404)->replyWithError(trans('api.languages_errors_404'));

        return $this->fetchMediaForLanguage($iso);
    }

    public function sync()
    {
        if (!file_exists(storage_path('data/jfm/languages'))) {
            mkdir(storage_path('data/jfm/languages'), 0777, true);
        }
        if (!file_exists(storage_path('data/jfm/feature-films'))) {
            mkdir(storage_path('data/jfm/feature-films'), 0777, true);
        }

        $this->syncLanguages();
        $this->syncTypes();
    }

    private function syncTypes()
    {
        $media_components = $this->fetch('media-components');
        foreach ($media_components->mediaComponents as $component) $output[$component->subType][$component->mediaComponentId] = $component->title;
        file_put_contents(storage_path('/data/jfm/types.json'), json_encode(collect($output), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function syncLanguages()
    {
        $languages = collect($this->fetch('media-languages')->mediaLanguages)->pluck('languageId', 'iso3');
        file_put_contents(storage_path('/data/jfm/languages.json'),
            json_encode(collect($languages), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function fetchMediaForLanguage($iso)
    {
        // Fetch Language Id equivalent
        $languages   = $this->fetchLocal('languages.json');
        $language_id = $languages[$iso];
        if (!$language_id) {
            return $this->setStatusCode(404)->replyWithError(trans('api.languages_errors_404', []));
        }

        // Check to see if we have a File Cache
        if (!file_exists(storage_path("/data/jfm/languages/$iso.json"))) {
            // Fetch Media Component List
            $media_components = $this->fetch('media-components',
                ['languageIds' => $language_id, 'subTypes' => 'featureFilm']);
            $media_components = collect($media_components->mediaComponents)->pluck('mediaComponentId');
            file_put_contents(storage_path("/data/jfm/languages/$iso.json"),
                json_encode($media_components, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $media_components = $this->fetchLocal("languages/$iso.json");
        }

        // Store Media Component List for Language
        foreach ($media_components as $media_component) {
            $feature_films[$media_component] = $this->fetch("media-components/$media_component/languages/$language_id");
        }
        if(!file_exists(storage_path('/data/jfm/languages'))) mkdir(storage_path('/data/jfm/feature-films'));
        file_put_contents(storage_path('/data/jfm/feature-films/$iso.json'), json_encode($feature_films, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $feature_films;
    }

    private function fetch($path, array $params)
    {
        $paramString = '';
        foreach ($params as $key => $value) {
            $paramString .= '&' . $key . '=' . $value;
        }
        $path = $this->base_url . $path . '?_format=json&apiKey=' . $this->api_key . '&limit=3000' . $paramString;

        $results = json_decode(file_get_contents($path));
        if (isset($results->_embedded)) {
            return $results->_embedded;
        }
        return $results;
    }

    private function fetchLocal($path)
    {
        return json_decode(file_get_contents(storage_path("/data/jfm/$path")), true);
    }
}
