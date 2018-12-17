<?php

namespace Tests\Integration;

use App\Models\User\Study\Highlight;
use App\Models\User\User;

class UserHighlightTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_highlights.index
     * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key={key}
     * @see      \App\Http\Controllers\User\HighlightsController::index
     * @group    V4
     * @test
     */
    public function highlightIndexErrors()
    {
        // User 404
        $path = route('v4_highlights.index', array_add($this->params, 'user_id', 'not-a-real-user'));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertStatus(404);

        // Project 401
        $user_id = User::inRandomOrder()->select('id')->first()->id;
        $new_params = ['user_id' => $user_id, 'project_id' => 'not-a-real-project'];
        $path = route('v4_highlights.index', array_merge($this->params, $new_params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertStatus(401);
    }

    /**
     * @category V4_API
     * @category Route Name: v4_highlights.index
     * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key={key}
     * @see      \App\Http\Controllers\User\HighlightsController::index
     * @group    V4
     * @test
     */
    public function highlightIndexSelects()
    {
        $highlight = Highlight::inRandomOrder()->first();
        $new_params = [
            'user_id'    => $highlight->user_id,
            'bible_id'   => $highlight->bible_id,
            'book_id'    => $highlight->book_id,
            'chapter'    => $highlight->chapter
        ];
        $path = route('v4_highlights.index', array_merge($this->params, $new_params));

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
    }

    /**
     * @category V4_API
     * @category Route Name: v4_highlights
     * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key={key}
     * @see      \App\Http\Controllers\User\HighlightsController
     * @group    V4
     * @test
     */
    public function highlights()
    {
        $path = route('v4_highlights.index', array_add($this->params, 'user_id', 5));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $test_highlight_post = [
            'bible_id'          => 'ENGESV',
            'user_id'           => 5,
            'book_id'           => 'GEN',
            'chapter'           => '1',
            'verse_start'       => '1',
            'reference'         => 'Genesis 1:1',
            'highlight_start'   => '10',
            'highlighted_words' => '40',
            'highlighted_color' => '#fff000',
        ];

        $path = route('v4_highlights.store', array_add($this->params, 'user_id', 5));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->post($path, $test_highlight_post);
        $response->assertSuccessful();

        $test_highlight = json_decode($response->getContent())->data;


        $path = route('v4_highlights.update', array_merge(['user_id' => 5,'highlight_id' => $test_highlight->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->put($path, ['highlighted_color' => '#ff1100']);
        $response->assertSuccessful();


        $path = route('v4_highlights.destroy', array_merge(['user_id' => 5,'highlight_id' => $test_highlight->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->delete($path);
        $response->assertSuccessful();
    }
}
