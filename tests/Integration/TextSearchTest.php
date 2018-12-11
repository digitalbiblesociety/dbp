<?php

namespace Tests\Integration;

use App\Models\Bible\BibleVerse;

class TextSearchTest extends ApiV4Test
{
    public $params = [];

    public function setUp()
    {
        parent::setUp();
        $this->params = ['v' => 4, 'key' => '1234'];
    }

    /* - Feature -------------------------*/
    /**
     * @category V4_API
     * @category Route Name: v4_text_search
     * @category Route Path: https://api.dbp.test/search?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\TextController::search
     * @group    V4
     * @test
     */
    public function basicEnglishSearchReturnsNonEmptyResultsFromKingJames()
    {
        $path = route('v4_text_search', ['fileset_id' => 'ENGKJV', 'query' => 'God', 'limit' => 5] + $this->params);
        $results = json_decode($this->get($path)->getContent())->data;

        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertContains('God', $result->verse_text);
        }
    }

    /**
     *
     * @category V4_API
     * @category Route Name: v4_text_search
     * @category Route Path: https://api.dbp.test/search?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\TextController::search
     * @group    V4
     * @test
     */
    public function onlyOneResultReturnsForAbaddon()
    {
        $path = route('v4_text_search', ['fileset_id' => 'ENGKJV', 'query' => 'Abaddon', 'limit' => 5] + $this->params);
        $results = json_decode($this->get($path)->getContent())->data;
        $this->assertCount(1, $results);
    }

    /**
     *
     * @category V4_API
     * @category Route Name: v4_text_search
     * @category Route Path: https://api.dbp.test/search?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\TextController::search
     * @group    V4
     * @test
     */
    public function nonMatchingSearchResultsReturnsEmpty()
    {
        $path = route('v4_text_search', ['fileset_id' => 'ENGKJV', 'query' => 'supercalafragalisticz'] + $this->params);
        $results = json_decode($this->get($path)->getContent())->data;
        $this->assertEmpty($results);
    }

    /* - Swagger -------------------------*/
    /**
     * @category V4_API
     * @category Route Name: v4_text_search
     * @category Route Path: https://api.dbp.test/search?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\TextController::search
     * @group    V4
     * @test
     */
    public function v4SwaggerForTextSearch()
    {
        $path = route('v4_text_search', array_merge(['fileset_id' => 'ENGKJV','query' => 'God'], $this->params));
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V2_Text
     * @see \App\Http\Controllers\Bible\TextController::search()
     * @category Swagger ID: TextSearch
     * @category Route Name: v2_text_search
     * @link Route Path: https://api.dbp.test/text/search?v=2&query=God&dam_id=ENGESV&limit=5&pretty&key={key}
     * @group    V2
     * @test
     */
    public function v2SwaggerForTextSearch()
    {
        $this->params['v'] = 2;
        $bible_verse = BibleVerse::with('fileset')->where('id', random_int(1, BibleVerse::count()))->first();
        $word = $this->selectSearchableWord($bible_verse);

        $this->params['dam_id'] = $bible_verse->fileset->id;
        $this->params['asset_id'] = $bible_verse->fileset->asset_id;
        $this->params['query']  = preg_replace("/(?![.=$'€%-])\p{P}/u", '', $word);
        $this->params['limit']  = 6;

        $response = $this->withHeaders($this->params)->get(route('v2_text_search'), $this->params);
        $response_content = json_decode($response->getContent());
        $response->assertSuccessful();

        echo "\n Testing ".route('v2_text_search', $this->params);

        $this->assertLessThanOrEqual($this->params['limit'], count($response_content[1]));
        foreach ($response_content[1] as $verse) {
            $this->assertContains($word, $verse->verse_text, 'Search Term Not Present in Result', 1);
        }
    }


    /**
     * @category V2_Text
     * @see \app\Http\Controllers\TextController::searchGroup()
     * @category Swagger ID: TextSearchGroup
     * @category Route Name: v2_text_search_group
     * @link Route Path: https://api.dbp.test/text/searchgroup?v=2&query=God&dam_id=ENGESV&limit=5&pretty&key={key}
     * @group    V2
     * @test
     */
    public function v2SwaggerForTextSearchGroup()
    {
        $this->params['v'] = 2;
        $bible_verse = BibleVerse::with('fileset')->where('id', random_int(1, BibleVerse::count()))->first();
        $word = $this->selectSearchableWord($bible_verse);

        $this->params['dam_id'] = $bible_verse->fileset->id;
        $this->params['asset_id'] = $bible_verse->fileset->asset_id;
        $this->params['query']  = preg_replace("/(?![.=$'€%-])\p{P}/u", '', $word);
        $this->params['limit']  = 5;

        echo "\n Testing ".route('v2_text_search_group', $this->params);

        $response = $this->withHeaders($this->params)->get(route('v2_text_search_group'), $this->params);
        $response_content = json_decode($response->getContent());
        $response->assertSuccessful();

        foreach ($response_content[1] as $verse) {
            $this->assertContains($word, $verse->verse_text, 'Search Term Not Present in Result', 1);
        }
    }

    private function selectSearchableWord($bible_verse)
    {
        $words = explode(' ', $bible_verse->verse_text);

        // sort values by longest
        usort($words, function ($a, $b) {
            return \strlen($b) <=> \strlen($a);
        });

        // return one word taken from the top 5 longest, stripped of punctuation
        return preg_replace("/(?![.=$'€%-])\p{P}/u", '', collect($words)->take(5)->random(1)->first());
    }
}
