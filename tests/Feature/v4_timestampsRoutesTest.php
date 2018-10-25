<?php

namespace Tests\Feature;

use App\Models\Bible\BibleFileset;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class v4_timestampsRoutesTest extends API_V4_Test
{

	/**
	 * @category V4_API
	 * @category Route Name: v4_timestamps'
	 * @category Route Path: https://api.dbp.test/timestamps?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController::availableTimestamps
	 */
	public function test_v4_timestamps()
	{
		$path = route('v4_timestamps', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_timestamps.tag
	 * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{query}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController::timestampsByTag
	 */
	public function test_v4_timestamps_tag()
	{
		$additional_params = [
			'audio_fileset_id' => 'ENGESVO2DA',
			'text_fileset_id' => 'ENGESV',
			'query' => 'God'
		];

		$path = route('v4_timestamps.tag', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_timestamps.verse
	 * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{book}/{chapter}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController::timestampsByReference
	 */
	public function test_v4_timestamps_verse()
	{
		$path = route('v4_timestamps.verse', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

}
