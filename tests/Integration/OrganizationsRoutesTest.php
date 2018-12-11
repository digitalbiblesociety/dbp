<?php

namespace Tests\Integration;

use App\Models\Organization\Organization;

class OrganizationsRoutesTest extends ApiV4Test
{
    /**
     * @category V4_API
     * @category Route Name: v4_organizations.all
     * @category Route Path: https://api.dbp.test/organizations/?v=4&key={key}
     * @see      \App\Http\Controllers\Organization\OrganizationsController::index
     * @group    V4
     * @test
     */
    public function organizationsAll()
    {
        $path = route('v4_organizations.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_organizations.one
     * @category Route Path: https://api.dbp.test/organizations/{organization_id}?v=4&key={key}
     * @see      \App\Http\Controllers\Organization\OrganizationsController::show
     * @group    V4
     * @test
     */
    public function organizationsOne()
    {
        $path = route('v4_organizations.one', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_organizations.compare
     * @category Route Path: https://api.dbp.test/organizations/compare/{org1}/to/{org2}?v=4&key={key}
     * @see      \App\Http\Controllers\Organization\OrganizationsController::compare
     * @group    V4
     * @test
     */
    public function organizationsCompare()
    {
        $organizations = Organization::inRandomOrder()->take(2)->get();
        $path = route('v4_organizations.compare', array_merge(['org1' => $organizations->first()->slug, 'org2' => $organizations->last()->slug], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }
}
