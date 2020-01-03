<?php

namespace Tests\Integration;

use App\Models\User\Study\Highlight;
use App\Models\User\User;
use App\Models\User\Key;

use Illuminate\Support\Arr;

class UserHighlightTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_highlights.index
     * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key={key}
     * @see      \App\Http\Controllers\User\HighlightsController::index
     * @group    V4
     * @group    travis
     * @test
     */
    public function highlightIndexErrors()
    {
        // User 404
        $path = route('v4_highlights.index', Arr::add($this->params, 'user_id', 'not-a-real-user'));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertStatus(404);
    }

    /**
     * @category V4_API
     * @category Route Name: v4_highlights
     * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key={key}
     * @see      \App\Http\Controllers\User\HighlightsController
     * @group    V4
     * @group    travis
     * @test
     */
    public function highlights()
    {
        $key = Key::where('key', $this->key)->first();
        $path = route('v4_highlights.index', Arr::add($this->params, 'user_id', $key->user_id));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $test_highlight_post = [
            'bible_id'          => 'ENGESV',
            'user_id'           => $key->user_id,
            'book_id'           => 'GEN',
            'chapter'           => '1',
            'verse_start'       => '1',
            'reference'         => 'Genesis 1:1',
            'highlight_start'   => '10',
            'highlighted_words' => '40',
            'highlighted_color' => '#fff000',
        ];

        $path = route('v4_highlights.store', Arr::add($this->params, 'user_id', $key->user_id));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->post($path, $test_highlight_post);
        $response->assertSuccessful();
        $test_highlight = json_decode($response->getContent())->data;


        // Highlight Index Selects
        $highlight = Highlight::where('user_id', $key->user_id)->inRandomOrder()->first();
        $new_params = [
            'user_id'    => $highlight->user_id,
            'bible_id'   => $highlight->bible_id,
            'book_id'    => $highlight->book_id,
            'chapter'    => $highlight->chapter
        ];
        $path = route('v4_highlights.index', array_merge($this->params, $new_params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $responseData = collect(json_decode($response->getContent())->data);
        $response->assertSuccessful();

        // Only one Bible ID should exist since bible_id is provided
        $this->assertCount(1, $responseData->pluck('bible_id')->unique());

        // And that bible_id should be the one provided
        $this->assertEquals($responseData->first()->bible_id, $highlight->bible_id);

        // The book_id should be the one provided
        $this->assertEquals($responseData->first()->book_id, $highlight->book_id);

        // The chapter should be the one provided
        $this->assertEquals($responseData->first()->chapter, $highlight->chapter);

        // Highlight Update
        $path = route('v4_highlights.update', array_merge(['user_id' => $key->user_id,'highlight_id' => $test_highlight->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->put($path, ['highlighted_color' => '#ff1100']);
        $response->assertSuccessful();

        // Highlight Destroy
        $path = route('v4_highlights.destroy', array_merge(['user_id' => $key->user_id,'highlight_id' => $test_highlight->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->delete($path);
        $response->assertSuccessful();
    }
}
