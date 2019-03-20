<?php
namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;
use App\Models\Country\CountryLanguage;
use App\Models\Language\Language;
use App\Traits\AccessControlAPI;
use App\Transformers\V2\LibraryCatalog\LanguageListingTransformer;
use App\Transformers\V2\CountryLanguageTransformer;
use Illuminate\Http\JsonResponse;

class LanguageControllerV2 extends APIController
{
    use AccessControlAPI;

    /**
     * @OA\Get(
     *     path="/library/language/",
     *     tags={"Library Catalog"},
     *     summary="Returns the list of languages",
     *     description="Returns the List of Languages",
     *     operationId="v2_library_language",
     *     @OA\Parameter(in="query",name="code",description="Get the entry for a three letter language code",@OA\Schema(ref="#/components/schemas/Language/properties/iso")),
     *     @OA\Parameter(in="query",name="name",description="Get the entry for a part of a language name in either native language or English",@OA\Schema(type="string",example="Spanish")),
     *     @OA\Parameter(in="query",name="full_word",description="Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string new is anywhere in the language name, like in Newari and Awa for Papua New Guinea. When true, it will only return volumes where the language name contains the full word 'new', like in `Awa for Papua New Guinea`. Default is false",@OA\Schema(type="boolean",default=false,example=false)),
     *     @OA\Parameter(in="query",deprecated=true,name="family_only",description="When set to true the returned list is of only legal language families. The default is false",@OA\Schema(type="boolean")),
     *     @OA\Parameter(in="query",deprecated=true,name="possibilities",description="When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria",@OA\Schema(type="boolean",default=true,example=true)),
     *     @OA\Parameter(in="query",name="sort_by",description="Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'",@OA\Schema(ref="#/components/schemas/Asset/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_language"))
     *     )
     * )
     *
     * return JsonResponse
     */
    public function languageListing()
    {
        // Params
        $code                  = checkParam('code');
        $name                  = checkParam('name');
        $full_word             = checkParam('full_word') ?? 'false';
        $sort_by               = checkParam('sort_by') ?? 'name';

        // Caching Logic
        $cache_string = strtolower('v' . $this->v . '_languages_' . $code.$full_word.$name.$sort_by);
        $cached_languages = \Cache::remember($cache_string, now()->addDay(), function () use ($code, $full_word, $name, $sort_by) {
            $languages = Language::select(['id', 'iso2B', 'iso', 'name'])->orderBy($sort_by)
                ->when($code, function ($query) use ($code) {
                    return $query->where('iso', $code);
                })
                ->has('filesets')
                // Filter results by language name when set
                ->when($name, function ($query) use ($name, $full_word) {
                    return $query->whereHas('translations', function ($query) use ($name, $full_word) {
                        $added_space = ($full_word === 'true') ? ' ': '';
                        $query->where('name', 'like', '%' . $name . $added_space . '%')->orWhere('name', $name);
                    });
                })->get();
            return fractal($languages, new LanguageListingTransformer(), $this->serializer);
        });

        return $this->reply($cached_languages);
    }


    /**
     * Handle the Country Lang route for V2
     *
     * @OA\Get(
     *     path="/country/countrylang/",
     *     tags={"Country Language"},
     *     summary="Returns Languages and the countries associated with them",
     *     description="Filter languages by a specified country code or filter countries by specified language code.
               Country flags can also be retrieved by requesting one of the permitted image sizes. Languages can also be
               sorted by the country code (default) and the language code.",
     *     operationId="v2_country_lang",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(
     *         name="lang_code",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/Language/properties/iso"),
     *         description="Get records by ISO language code"
     *     ),
     *     @OA\Parameter(
     *         name="country_code",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/Country/properties/id"),
     *         description="Get records by ISO country code",
     *     ),
     *     @OA\Parameter(
     *         name="additional",
     *         in="query",
     *         @OA\Schema(type="integer",enum={0,1},default=0),
     *         description="Get colon separated list of optional countries"
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         @OA\Schema(type="string",enum={"country_code","lang_code"},default="country_code"),
     *         description="Sort by lang_code or country_code"
     *     ),
     *     @OA\Parameter(
     *         name="img_type",
     *         in="query",
     *         @OA\Schema(type="string",enum={"png","svg"},default="png"),
     *         description="Includes a country flag image of the specified file type"
     *     ),
     *     @OA\Parameter(
     *         name="img_size",
     *         in="query",
     *         @OA\Schema(type="string",example="160X120",enum={"40x30","80x60","160x120","320x240","640x480","1280x960"}),
     *         description="Include country flags in entries in requested size. This no longer generates images but
                   rather selects them from a recommended list: 40x30, 80x60, 160X120, 320X240, 640X480, or 1280X960"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_country_lang"))
     *     )
     * )
     *
     *
     * @return JsonResponse
     */
    public function countryLang()
    {
        $sort_by            = checkParam('sort_by') ?? 'country_id';
        $lang_code          = checkParam('lang_code');
        $country_code       = checkParam('country_code');
        $img_size           = checkParam('img_size');
        $img_type           = checkParam('img_type') ?? 'png';
        $additional         = checkParam('additional');

        $access_control = $this->accessControl($this->key);
        $cache_string   = 'v2_country_lang_' . $sort_by . $lang_code . $country_code . $img_size . $img_type .
                          $additional . $access_control->string;

        $countryLang = \Cache::remember($cache_string, now()->addDay(),
            function () use ($sort_by, $lang_code, $country_code, $additional, $img_size, $img_type, $access_control) {

                $country_langs = CountryLanguage::with(['country', 'language' => function($query) use($additional) {
                        $query->when($additional, function ($subquery) {
                            $subquery->with('countries');
                        });
                    }])
                    ->whereHas('language', function($query) use($access_control, $lang_code, $additional) {
                        $query->whereHas('filesets', function ($subquery) use ($access_control, $lang_code) {
                            $subquery->whereIn('hash_id', $access_control->hashes);
                            if($lang_code) {
                                $subquery->where('iso', $lang_code);
                            }
                        });
                    })
                    ->whereHas('country', function($query) use($country_code) {
                        $query->when($country_code, function ($subquery) use ($country_code) {
                            $subquery->where('country_id', $country_code);
                        });
                    })
                    ->orderBy($sort_by, 'desc')->get()->each(function ($item, $key) use($img_size, $img_type) {
                        $path  = 'https://dbp-mcdn.s3.us-west-2.amazonaws.com/flags/full';
                        $path .= (($img_type === 'svg') ? '/svg/' : "/$img_size/");
                        $path .= strtoupper($item->country_id).'.'.$img_type;

                        $item->country_image = $path;
                    });

                return fractal($country_langs, new CountryLanguageTransformer(), $this->serializer);
            }
        );

        return $this->reply($countryLang);
    }

    /**
     *
     * @OA\Get(
     *     path="/library/volumelanguage/",
     *     tags={"Library Catalog"},
     *     summary="Returns the list of languages",
     *     description="This method retrieves the list of languages for available volumes and the related volume data in
               the system according to the filter specified.",
     *     operationId="v2_library_volumeLanguageFamily",
     *     @OA\Parameter(
     *         name="language_code",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="The three letter language code"
     *     ),
     *     @OA\Parameter(
     *         name="root",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="Can be used to restrict the response to only languages that start with Quechua for example."
     *     ),
     *     @OA\Parameter(
     *         name="media",
     *         in="query",
     *         @OA\Schema(type="string",enum={"text","audio","video"}),
     *         description="The format of languages the caller is interested in."
     *     ),
     *     @OA\Parameter(
     *         name="full_word",
     *         in="query",
     *         @OA\Schema(type="boolean"),
     *         description="Consider the language name as being a full word. For instance, when false, `cat` will return
                   volumes where the string cat is anywhere in the language name like `catalan` and `Cuicatec, Teutila`.
                   This value defaults to false."
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="Publishing status of volume. The default is live"
     *     ),
     *     @OA\Parameter(
     *         name="resolution",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="organization_id",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="The organization id to filter languages by."
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_language"))
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function volumeLanguage()
    {
        $iso             = checkParam('language_code');
        $root            = checkParam('root');
        $media           = checkParam('media');
        $full_word       = (boolean) checkParam('full_word');
        $organization_id = checkParam('organization_id');
        
        $cache_string = strtolower('volumeLanguage' . $root . $iso . $media . $organization_id);
        $languages = \Cache::remember($cache_string, now()->addDay(), function () use ($root, $iso, $media, $full_word, $organization_id) {
                $languages = Language::has('filesets')
                    ->includeCurrentTranslation()
                    ->includeAutonymTranslation()
                    ->filterableByIsoCode($iso)
                    ->filterableByName($root, $full_word)
                    ->when($organization_id, function ($query) use ($organization_id) {
                        return $query->whereHas('filesets', function ($q) use ($organization_id) {
                            $q->where('organization_id', $organization_id);
                        });
                    })->when($media, function ($query) use ($media) {
                        return $query->whereHas(['bibles.filesets' => function ($query) use ($media) {
                            return $query->where('set_type_code', 'LIKE', $media.'%');
                        }]);
                    })->select([
                        'languages.id',
                        'languages.glotto_id',
                        'languages.iso',
                        'languages.name as backup_name',
                        'current_translation.name as name',
                        'autonym.name as autonym'
                    ])->with('parent')->get();

                return fractal($languages, new LanguageListingTransformer(), $this->serializer);
            }
        );
        return $this->reply($languages);
    }


    /**
     * API V2:
     * Returns of List of Macro-Languages that contain resources and their dialects
     *
     * @param language_code (optional): the three letter language code.
     * @param root (optional): the native language or English language language name root. Can be used to restrict the response to only languages that start with 'Quechua' for example
     * @param media (optional): [text|audio|video] - the format of languages the caller is interested in. This specifies if you want languages available in text or languages available in audio.
     * @param delivery (optional): [streaming|web_streaming|download|download_text|mobile|sign_language|local_bundled|podcast|mp3_cd|digital_download|bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming|mobile". 'any' means any of the supported methods (this list may change over time). 'none' means assets that are not approved for any of the supported methods. All returned by default.
     *
     * @deprecated full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
     * @deprecated status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
     * @deprecated resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
     *
     * @param organization_id : The id of an organization by which to filter the languages of available volumes.
     *
     *
     * @OA\Get(
     *     path="/library/volumelanguagefamily/",
     *     tags={"Library Catalog"},
     *     summary="Returns the list of languages",
     *     description="This method retrieves the list of language families for available volumes and the related volume
               data in the system according to the filter specified.",
     *     operationId="v2_library_volumeLanguageFamily",
     *     @OA\Parameter(
     *         name="language_code",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="root",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="media",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="delivery",
     *         in="query",
     *         deprecated=true,
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="full_word",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(
     *         name="resolution",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description=""
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/l10n"),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_language"))
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function volumeLanguageFamily()
    {
        $iso             = checkParam('language_code');
        $root            = checkParam('root');
        $media           = checkParam('media');
        $organization_id = checkParam('organization_id');

        $access_control = $this->accessControl($this->key);

        $cache_string = strtolower('volumeLanguageFamily' . $root . $iso . $media . $organization_id);
        $languages = \Cache::remember($cache_string, now()->addDay(), function () use ($root, $iso, $access_control, $media, $organization_id) {
            $languages = Language::with('bibles')->with('dialects')
                    ->includeAutonymTranslation()
                    ->includeCurrentTranslation()
                    ->whereHas('filesets', function ($query) use ($access_control,$organization_id,$media) {
                        $query->whereIn('hash_id', $access_control->hashes);
                        if ($organization_id) {
                            $query->whereHas('copyright', function ($query) use ($organization_id) {
                                $query->where('organization_id', $organization_id);
                            });
                        }
                        if ($media) {
                            $query->where('set_type_code', 'LIKE', $media.'%');
                        }
                    })
                    ->with(['dialects.childLanguage' => function ($query) {
                        $query->select(['id', 'iso']);
                    }])
                    ->when($iso, function ($query) use ($iso) {
                        return $query->where('iso', $iso);
                    })
                    ->when($root, function ($query) use ($root) {
                        return $query->where('name', 'LIKE', '%' . $root . '%');
                    })
                    ->select(
                        [
                            'current_translation.name as name',
                            'autonym.name as autonym',
                            'languages.iso',
                            'languages.iso2B',
                            'languages.iso2T',
                            'languages.iso1'
                        ]
                    )
                    ->get();

            return fractal($languages, new LanguageListingTransformer(), $this->serializer);
        });
        return $this->reply($languages);
    }
}
