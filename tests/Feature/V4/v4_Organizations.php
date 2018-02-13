<?php

namespace Tests\Feature\V4;

class v4_Organizations extends v4_BaseTest {

	/**
	 *
	 * Tests the Organizations Index Route
	 *
	 * @category v4_User
	 * @see OrganizationsController::index()
	 * @category Swagger ID: Organization
	 * @category Route Names: v4_organizations.index
	 * @link Route Path: https://api.dbp.dev/organizations?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_organizations_index()
	{
		echo "\nTesting Organizations Index: ".route('v4_organizations.index', $this->params);
		$response = $this->get(route('v4_organizations.index'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

	/**
	 *
	 * Tests the Organizations Show Route
	 *
	 * @category v4_User
	 * @see OrganizationsController::index()
	 * @category Swagger ID: Organization
	 * @category Route Names: v4_organizations.one
	 * @link Route Path: https://api.dbp.dev/organizations/1?v=4&pretty&key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824
	 *
	 */
	public function test_v4_organizations_show()
	{
		echo "\nTesting Organizations Show: ".route('v4_organizations.one', $this->params);
		$response = $this->get(route('v4_organizations.one'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('Project')]);
	}

}