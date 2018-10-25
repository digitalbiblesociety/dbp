<?php

namespace Tests\Feature;

use App\Models\Organization\Organization;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class v4_organizationsRoutesTest extends API_V4_Test
{
	/**
	 * @category V4_API
	 * @category Route Name: v4_organizations.all
	 * @category Route Path: https://api.dbp.test/organizations/?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController::index
	 */
	public function test_v4_organizations_all()
	{
		$path = route('v4_organizations.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_organizations.one
	 * @category Route Path: https://api.dbp.test/organizations/{organization_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController::show
	 */
	public function test_v4_organizations_one()
	{
		$path = route('v4_organizations.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_organizations.compare
	 * @category Route Path: https://api.dbp.test/organizations/compare/{org1}/to/{org2}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController::compare
	 */
	public function test_v4_organizations_compare()
	{
		$organizations = Organization::inRandomOrder()->take(2)->get();
		$path = route('v4_organizations.compare', array_merge(['org1' => $organizations->first()->slug, 'org2' => $organizations->last()->slug], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
}
