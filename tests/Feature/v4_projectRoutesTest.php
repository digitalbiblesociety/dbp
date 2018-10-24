<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class v4_projectRoutesTest extends TestCase
{

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects.index
	 * @category Route Path: https://api.dbp.test/projects?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController::index
	 */
	public function test_v4_projects_index()
	{
		$path = route('v4_projects.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects.show
	 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController::show
	 */
	public function test_v4_projects_show()
	{
		$path = route('v4_projects.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects.update
	 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController::update
	 */
	public function test_v4_projects_update()
	{
		$path = route('v4_projects.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects.store
	 * @category Route Path: https://api.dbp.test/projects?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController::store
	 */
	public function test_v4_projects_store()
	{
		$path = route('v4_projects.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects.destroy
	 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController::destroy
	 */
	public function test_v4_projects_destroy()
	{
		$path = route('v4_projects.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

}
