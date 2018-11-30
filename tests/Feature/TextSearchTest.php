<?php

namespace Tests\Feature;

use App\Models\Bible\BibleFileset;
use App\Models\User\AccessGroup;
use Tests\TestCase;

class TextSearchTest extends TestCase
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
            $this->assertContains('God',$result->verse_text);
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
        echo "\nTesting: $path";
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
        $public_domain_access_group = AccessGroup::with('filesets')->where('name', 'PUBLIC_DOMAIN')->first();
        $fileset_hashes = $public_domain_access_group->filesets->pluck('hash_id');
        $fileset = BibleFileset::with('files')->whereIn('hash_id', $fileset_hashes)->where('set_type_code', 'text_plain')->inRandomOrder()->first();

        $sophia = \DB::connection('sophia')->table(strtoupper($fileset->id).'_vpl')->inRandomOrder()->take(1)->first();
        $text = collect(explode(' ', $sophia->verse_text))->random(1)->first();

        $this->params['dam_id'] = $fileset->id;
        $this->params['query']  = $text;
        $this->params['limit']  = 5;

        echo "\nTesting: " . route('v2_text_search', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_text_search'), $this->params);

        $response->assertSuccessful();
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
        $public_domain_access_group = \App\Models\User\AccessGroup::with('filesets')->where('name', 'PUBLIC_DOMAIN')->first();
        $fileset_hashes = $public_domain_access_group->filesets->pluck('hash_id');
        $fileset = \App\Models\Bible\BibleFileset::with('files')->whereIn('hash_id', $fileset_hashes)->where('set_type_code', 'text_plain')->inRandomOrder()->first();

        $sophia = \DB::connection('sophia')->table(strtoupper($fileset->id).'_vpl')->inRandomOrder()->take(1)->first();
        $text = collect(explode(' ', $sophia->verse_text))->random(1)->first();

        $this->params['dam_id'] = $fileset->id;
        $this->params['query']  = $text;
        $this->params['limit']  = 5;

        echo "\nTesting: " . route('v2_text_search_group', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_text_search_group'), $this->params);
        $response->assertSuccessful();
    }
}