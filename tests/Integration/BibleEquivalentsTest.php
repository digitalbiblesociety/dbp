<?php

namespace Tests\Integration;

use App\Models\Bible\Bible;
use Tests\TestCase;

class BibleEquivalentsTest extends TestCase
{
    protected $params = ['key' => 'tighten_37518dau8gb891ub', 'v' => '4'];

    /**
     * @group v4_access
     * @test
     */
    public function equivalentsCanBefilteredByBibleID()
    {
        // Mock IP address uses North America and US by default
        $bible = factory(Bible::class)->make();

        $response = $this->get(route('v4_bible_equivalents.all', $this->params + ['bible_id' => $bible->id]));
        $response->assetSuccessful();

        $response_content = json_decode($response->getContent());
        dd($response_content);

        //$this->assertEquals(, 'PUBLIC_DOMAIN'); // Only public domain group for limited access
    }


}
