<?php

namespace Tests\Feature;

use App\Models\User\AccessType;
use App\Models\User\Key;
use App\Traits\AccessControlAPI;
use Mockery as m;
use Torann\GeoIP\GeoIP;

class AccessControlTest extends ApiV4Test
{
    use AccessControlAPI;

    private function mockIpTest()
    {
        $geoipMock = m::mock(GeoIP::class);
        $geoipMock->shouldReceive('getLocation')->andReturn((object) ['continent' => 'NA', 'iso_code' => 'US']);
        $this->app->instance('geoip', $geoipMock);
    }

    /**
     *
     * @group v4_access
     * @test
     */
    public function accessAllowedBasic()
    {
        $access_controls = $this->accessControl(Key::inRandomOrder()->first()->key);

        $this->assertTrue(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertTrue(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }

    /**
     *
     * @group v4_access
     * @test
     */
    public function accessDeniedBasic()
    {
        $access_controls = $this->accessControl('this-is-not-a-real-api-key');

        $this->assertFalse(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertFalse(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }

    /**
     *
     * @group v4_access
     * @test
     */
    public function accessAllowedCountry()
    {
        $this->markTestIncomplete('This test can\'t be completed until we have functional seeds');
        $this->mockIpTest();
        $key = AccessType::where('country_id', '=', 'US')->first()->accessGroups()->first()->keys()->first()->key_id;

        $access_controls = $this->accessControl($key);

        $this->assertTrue(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertTrue(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }


    /**
     * @group v4_access
     * @test
     */
    public function accessLimitedCountry()
    {
        $this->markTestIncomplete('This test can\'t be completed until we have functional seeds');
        $this->mockIpTest();
        $key = AccessType::where('country_id', 'IN')->first()->accessGroups()->first()->keys()->first()->key_id;

        $access_controls = $this->accessControl($key);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertEquals($access_controls->string, 'PUBLIC_DOMAIN'); // Only public domain group for limited access
    }

    /**
     *
     * @group v4_access
     * @test
     */
    public function ContinentLimitedContentCantBeAccessedFromOtherContinents()
    {
        $this->markTestIncomplete('This test can\'t be completed until we have functional seeds');
        // Mock IP Test asserts North American Origin by Default
        $this->mockIpTest();

        $limited_fileset = AccessType::where('continent_id', '=', 'AS')->first()->accessGroups()->first()->filesets()->inRandomOrder()->first();
        $access_controls = $this->accessControl(Key::inRandomOrder()->first());

        $this->assertNotContains($limited_fileset->hash_id, $access_controls->hashes);
    }

    /**
     *
     * @group v4_access
     * @test
     */
    public function ContinentLimitedContentCanBeAccessedFromItsContinent()
    {
        $this->markTestIncomplete('This test can\'t be completed until we have functional seeds');
        // Mock IP asserts Asian Origin
        $this->mockIpTest();

        $allowed_fileset = AccessType::where('continent_id', 'NA')->first()->accessGroups()->first()->filesets()->inRandomOrder()->first();
        $access_controls = $this->accessControl(Key::inRandomOrder()->first());

        $this->assertNotContains($allowed_fileset->hash_id, $access_controls->hashes);
    }

}
