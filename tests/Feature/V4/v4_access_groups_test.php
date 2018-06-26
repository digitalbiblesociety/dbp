<?php

namespace Tests\Feature\V4;

use App\Http\Controllers\AccessGroupController;

class v4_access_groups_test extends v4_base_test {

	/**
	 *
	 * Tests the notes Index Route
	 *
	 * @category v4_access_groups
	 * @see AccessGroupController::index()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_access_groups.index
	 * @link Route Path: https://api.dbp.localhost/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_access_groups_index()
	{
		echo "\nTesting notes Index: " . route('v4_access_groups.index', $this->params);
		$response = $this->get(route('v4_access_groups.index'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('AccessGroup')]);
	}

	/**
	 *
	 * Tests the Notes Store Route
	 *
	 * @category v4_access_groups
	 * @see AccessGroupController::store()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_access_groups.store
	 * @link Route Path: https://api.dbp.localhost/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_access_groups_store()
	{
		echo "\nTesting notes store: " . route('v4_access_groups.store', $this->params);
		$response = $this->post(route('v4_access_groups.store'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('AccessGroup')]);
	}

	/**
	 *
	 * Tests the Notes Store Route
	 *
	 * @category v4_access_groups
	 * @see AccessGroupController::update()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_access_groups.update
	 * @link Route Path: https://api.dbp.localhost/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_access_groups_update()
	{
		echo "\nTesting notes update: " . route('v4_access_groups.update', $this->params);
		$response = $this->put(route('v4_access_groups.update'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('AccessGroup')]);
	}

	/**
	 *
	 * Tests the Notes Store Route
	 *
	 * @category v4_access_groups
	 * @see AccessGroupController::update()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_access_groups.store
	 * @link Route Path: https://api.dbp.localhost/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_access_groups_destroy()
	{
		echo "\nTesting notes destroy: " . route('v4_access_groups.destroy', $this->params);
		$response = $this->delete(route('v4_access_groups.destroy'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('AccessGroup')]);
	}

}