<?php

namespace Tests\Integration;

use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Language\NumeralSystem;

class WikiRoutesTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_countries
     * @category Route Path: https://api.dbp.test/countries?v=4&key={key}
     * @see      \App\Http\Controllers\Wiki\CountriesController
     * @group V4
     * @group V4_wiki
     * @group    travis
     * @test
     */
    public function countries()
    {
        $path = route('v4_countries.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $path = route('v4_countries.jsp', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $current_country = Country::inRandomOrder()->first();
        $path = route('v4_countries.one', array_add($this->params, 'country_id', $current_country->id));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }


    /**
     * @category V4_API
     * @category Route Name: v4_languages.all
     * @category Route Path: https://api.dbp.test/languages?v=4&key={key}
     * @see      \App\Http\Controllers\Wiki\LanguagesController::index
     * @group V4
     * @group V4_wiki
     * @group    travis
     * @test
     */
    public function languages()
    {
        $path = route('v4_languages.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $current_language = Language::inRandomOrder()->first();
        $path = route('v4_languages.one', array_add($this->params, 'language_id', $current_language->id));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_alphabets
     * @category Route Path: https://api.dbp.test/alphabets?v=4&key={key}
     * @see      \App\Http\Controllers\Wiki\AlphabetsController
     * @group V4
     * @group V4_wiki
     * @group    travis
     * @test
     */
    public function wikiAlphabets()
    {
        $path = route('v4_alphabets.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $current_alphabet = Alphabet::inRandomOrder()->first();
        $path = route('v4_alphabets.one', array_add($this->params, 'alphabet_id', $current_alphabet->script));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }


    /**
     * @category V4_API
     * @category Route Name: v4_numbers
     * @category Route Path: https://api.dbp.test/numbers/?v=4&key={key}
     * @see      \App\Http\Controllers\Wiki\NumbersController
     * @group V4
     * @group V4_wiki
     * @group    travis
     * @test
     */
    public function wikiNumbers()
    {
        $path = route('v4_numbers.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $numeralSystem = NumeralSystem::inRandomOrder()->first();
        $path = route('v4_numbers.one', array_merge(['id' => $numeralSystem->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }
}
