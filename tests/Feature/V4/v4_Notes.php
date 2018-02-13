<?php

namespace Tests\Feature\V4;

class v4_Notes extends v4_BaseTest {

	/**
	 *
	 * Tests the notes Index Route
	 *
	 * @category v4_User_Notes
	 * @see UserNotesController::index()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_notes.index
	 * @link Route Path: https://api.dbp.dev/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_notes_index()
	{
		echo "\nTesting notes Index: ".route('v4_notes.index', $this->params);
		$response = $this->get(route('v4_notes.index'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

	/**
	 *
	 * Tests the Notes Store Route
	 *
	 * @category v4_User_Notes
	 * @see UserNotesController::store()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_notes.store
	 * @link Route Path: https://api.dbp.dev/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_notes_store()
	{
		echo "\nTesting notes store: ".route('v4_notes.store', $this->params);
		$response = $this->post(route('v4_notes.store'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

	/**
	 *
	 * Tests the Notes Store Route
	 *
	 * @category v4_User_Notes
	 * @see UserNotesController::update()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_notes.store
	 * @link Route Path: https://api.dbp.dev/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_notes_update()
	{
		echo "\nTesting notes update: ".route('v4_notes.update', $this->params);
		$response = $this->put(route('v4_notes.update'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

	/**
	 *
	 * Tests the Notes Store Route
	 *
	 * @category v4_User_Notes
	 * @see UserNotesController::update()
	 * @category Swagger ID: Notes
	 * @category Route Names: v4_notes.store
	 * @link Route Path: https://api.dbp.dev/notes?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_notes_destroy()
	{
		echo "\nTesting notes destroy: ".route('v4_notes.destroy', $this->params);
		$response = $this->delete(route('v4_notes.destroy'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

}