<?php

namespace Tests\Feature\V4;

class v4_Projects extends v4_BaseTest {

	/**
	 *
	 * Tests the Project Index Route
	 *
	 * @category v4_User
	 * @see ProjectsController::index()
	 * @category Swagger ID: User
	 * @category Route Names: v4_projects.index
	 * @link Route Path: https://api.dbp.dev/projects?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_projects_index()
	{
		echo "\nTesting Projects Index: ".route('v4_projects.index', $this->params);
		$response = $this->get(route('v4_projects.index'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

	/**
	 *
	 * Tests the Project Creation Route
	 *
	 * @category v4_User
	 * @see ProjectsController::store()
	 * @category Swagger ID: User
	 * @category Route Names: v4_projects.store
	 * @link Route Path: https://api.dbp.dev/projects?v=4&pretty
	 *
	 */
	public function test_v4_projects_store()
	{
		echo "\nTesting Projects Store: ".route('v4_projects.store', $this->params);
		$response = $this->post(route('v4_projects.store'), [
			'id'              => "org.inscript.biblicos.v2",
			'name'            => "inScript: Biblicos",
			'url_avatar'      => 'url_avatar.jpg',
			'url_avatar_icon' => 'url_avatar_icon.jpg',
			'url_site'        => "https://biblicos.org/",
			'description'     => "Biblicos",
		], $this->params,$this->params);


		$response->assertSuccessful();
//		$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

	/**
	 *
	 * Tests the Project Show Route
	 *
	 * @category v4_User
	 * @see ProjectsController::show()
	 * @category Swagger ID: User
	 * @category Route Names: v4_projects.show
	 * @link Route Path: https://api.dbp.dev/projects/org.inscript.biblicos.v2?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_projects_show()
	{
		$params = array_merge($this->params,['project_id' => 'org.inscript.biblicos.v2']);
		echo "\nTesting Projects Show: ".route('v4_projects.show',$params);
		$response = $this->get(route('v4_projects.show', $params),$params);
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Project Update Route
	 *
	 * @category v4_User
	 * @see ProjectsController::update()
	 * @category Swagger ID: User
	 * @category Route Names: v4_projects.update
	 * @link Route Path: https://api.dbp.dev/projects/org.inscript.biblicos.v2?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_projects_update()
	{
		$params = array_merge($this->params,['project_id' => 'org.inscript.biblicos.v2']);

		$project = Project::find('org.inscript.biblicos.v2');
		$project->name = "New inScript";
		$project->save();

		$response = $this->get(route('v4_projects.update',$params),$params);
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Project Destroy Route
	 *
	 * @category v4_User
	 * @see ProjectsController::destroy()
	 * @category Swagger ID: User
	 * @category Route Names: v4_projects.destroy
	 * @link Route Path: https://api.dbp.dev/projects/org.inscript.biblicos.v2?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_projects_destroy()
	{
		$params = array_merge($this->params,['project_id' => 'org.inscript.biblicos.v2','_token' => csrf_token()]);
		echo "\nTesting Projects Destroy: ".route('v4_projects.destroy',$params);
		$response =$this->delete(route('v4_projects.destroy',$params),$params,$params)->assertSuccessful();
	}
}