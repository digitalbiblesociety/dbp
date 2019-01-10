<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;

use App\Models\Country\JoshuaProject;
use App\Models\Country\Country;
use App\Transformers\CountryTransformer;

class CountriesController extends APIController
{

    /**
     * Returns Countries
     *
     * @version 4
     * @category v4_countries.all
     * @link http://bible.build/countries - V4 Access
     * @link https://api.dbp.test/countries?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
     *
     * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
     *
     * @OA\Get(
     *     path="/countries/",
     *     tags={"Countries"},
     *     summary="Returns Countries",
     *     description="Returns the List of Countries",
     *     operationId="v4_countries.all",
     *     @OA\Parameter(
     *          name="l10n",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Language/properties/iso"),
     *          description="When set to a valid three letter language iso, the returning results will be localized in
     *                       the language matching that iso. (If an applicable translation exists)."
     *     ),
     *     @OA\Parameter(
     *          name="has_filesets",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id"),
     *          description="Filter the returned countries to those containing languages that have filesets",
     *     ),
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Asset/properties/id"),
     *          description="Filter the returned countries to only those containing filesets for a specific asset",
     *     ),
     *     @OA\Parameter(
     *          name="include_languages",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="When set to true, the return will include the major languages used in each country.
     *                       You may optionally also include the language names by setting it to `with_names`",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_countries.all")
     *         )
     *     )
     * )
     *
     *
     */
    public function index()
    {
        if (!$this->api) {
            return view('wiki.countries.index');
        }
        if (config('app.env') === 'local') {
            ini_set('memory_limit', '864M');
        }

        $filesets = checkParam('has_filesets') ?? true;
        $asset_id = checkParam('asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $languages = checkParam('include_languages');

        $cache_string = strtolower('countries' . $GLOBALS['i18n_iso'] . $filesets . $asset_id . $languages);

        $countries = \Cache::remember($cache_string, 1600, function () use ($filesets, $asset_id, $languages) {
            $countries = Country::with('currentTranslation')->when($filesets, function ($query) use ($asset_id) {
                $query->whereHas('languages.bibles.filesets', function ($query) use ($asset_id) {
                    if ($asset_id) {
                        $query->where('asset_id', $asset_id);
                    }
                });
            })->get();
            if ($languages !== null) {
                $countries->load([
                    'languagesFiltered' => function ($query) use ($languages) {
                        if ($languages === 'with_names') {
                            $query->with(['translation' => function ($query) {
                                $query->where('language_translation_id', $GLOBALS['i18n_id']);
                            }]);
                        }
                    },
                ]);
            }
            return fractal()->collection($countries)->transformWith(new CountryTransformer());
        });
        return $this->reply($countries);
    }

    /**
     * Returns Joshua Project Country Information
     *
     * @version 4
     * @category v4_countries.jsp
     * @link http://bible.build/countries/joshua-project/ - V4 Access
     * @link https://api.dbp.test/countries/joshua-project?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_all - V4 Test Docs
     *
     * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function joshuaProjectIndex()
    {
        $cache_string = 'countries_jp_' . strtolower($GLOBALS['i18n_iso']);
        $joshua_project_countries = \Cache::remember($cache_string, 1600, function () {
            $countries = JoshuaProject::with(['country',
                'translations' => function ($query) {
                    $query->where('language_id', $GLOBALS['i18n_id']);
                },
            ])->get();

            return fractal($countries, CountryTransformer::class);
        });
        return $this->reply($joshua_project_countries);
    }

    /**
     * Returns the Specified Country
     *
     * @version 4
     * @category v4_countries.one
     * @link http://bible.build/countries/RU/ - V4 Access
     * @link https://api.dbp.test/countries/ru?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_countries_one - V4 Test Docs
     *
     * @OA\Get(
     *     path="/countries/{id}",
     *     tags={"Countries"},
     *     summary="Returns a single Country",
     *     description="Returns a single Country",
     *     operationId="v4_countries.one",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Country/properties/id")
     *     ),
     *     @OA\Parameter(name="communications",in="query",@OA\Schema(ref="#/components/schemas/CountryCommunication")),
     *     @OA\Parameter(name="economy",in="query",@OA\Schema(ref="#/components/schemas/CountryEconomy")),
     *     @OA\Parameter(name="energy",in="query",@OA\Schema(ref="#/components/schemas/CountryEnergy")),
     *     @OA\Parameter(name="geography",in="query",@OA\Schema(ref="#/components/schemas/CountryGeography")),
     *     @OA\Parameter(name="government",in="query",@OA\Schema(ref="#/components/schemas/CountryGovernment")),
     *     @OA\Parameter(name="issues",in="query",@OA\Schema(ref="#/components/schemas/CountryIssues")),
     *     @OA\Parameter(name="people",in="query",@OA\Schema(ref="#/components/schemas/CountryPeople")),
     *     @OA\Parameter(name="ethnicities",in="query",@OA\Schema(ref="#/components/schemas/CountryEthnicity")),
     *     @OA\Parameter(name="regions",in="query",@OA\Schema(ref="#/components/schemas/CountryRegion")),
     *     @OA\Parameter(name="religions",in="query",@OA\Schema(ref="#/components/schemas/CountryReligion")),
     *     @OA\Parameter(name="transportation",in="query",@OA\Schema(ref="#/components/schemas/CountryTransportation")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_countries.one")
     *         )
     *     )
     * )
     *
     * @param  string $id
     *
     * @return mixed $countries string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function show($id)
    {
        $cache_string = strtolower('countries_'. $id . $GLOBALS['i18n_iso']);
        $country = \Cache::remember($cache_string, 1600, function () use ($id) {
            $country = Country::with('languagesFiltered.bibles.translations')->find($id);
            if (!$country) {
                return $this->setStatusCode(404)->replyWithError(trans('api.countries_errors_404', ['id' => $id]));
            }
            return $country;
        });
        if (!is_a($country, Country::class)) {
            return $country;
        }
        $includes = $this->loadWorldFacts($country);
        return $this->reply(fractal($country, new CountryTransformer(), $this->serializer)->parseIncludes($includes));
    }

    private function loadWorldFacts($country)
    {
        $loadedProfiles = [];
        // World Factbook
        $profiles['communications'] = checkParam('communications') ?? 0;
        $profiles['economy']        = checkParam('economy') ?? 0;
        $profiles['energy']         = checkParam('energy') ?? 0;
        $profiles['geography']      = checkParam('geography') ?? 0;
        $profiles['government']     = checkParam('government') ?? 0;
        $profiles['issues']         = checkParam('issues') ?? 0;
        $profiles['people']         = checkParam('people') ?? 0;
        $profiles['ethnicities']    = checkParam('ethnicity') ?? 0;
        $profiles['regions']        = checkParam('regions') ?? 0;
        $profiles['religions']      = checkParam('religions') ?? 0;
        $profiles['transportation'] = checkParam('transportation') ?? 0;
        $profiles['joshuaProject']  = checkParam('joshuaProject') ?? 0;
        foreach ($profiles as $key => $profile) {
            if ($profile !== 0) {
                $country->load($key);
                if ($country->{$key} !== null) {
                    $loadedProfiles[] = $key;
                }
            }
        }
        return $loadedProfiles;
    }
}
