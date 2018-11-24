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
     * @test
     */
    public function accessAllowedCountry()
    {
        $this->mockIpTest();
        $key = AccessType::where('country_id', '=', 'US')->first()->accessGroups()->first()->keys()->first()->key_id;

        $access_controls = $this->accessControl($key);

        $this->assertTrue(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertTrue(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }

    /**
     * @test
     */
    public function accessAllowedContinent()
    {
        $this->mockIpTest();
        $key = AccessType::where('continent_id', '!=', null)->first()->accessGroups()->first()->keys()->first()->key_id;

        $access_controls = $this->accessControl($key);

        $this->assertTrue(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertTrue(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }

    /**
     * @test
     */
    public function accessDeniedCountry()
    {
        $this->mockIpTest();
        $key = AccessType::where('country_id', '=', 'IN')->first()->accessGroups()->first()->keys()->first()->key_id;

        $access_controls = $this->accessControl($key);

        $this->assertFalse(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertFalse(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }

    /**
     * @test
     */
    public function accessDeniedContinent()
    {
        $this->mockIpTest();
        $key = AccessType::where('continent_id', '=', 'AS')->first()->accessGroups()->first()->keys()->first()->key_id;

        $access_controls = $this->accessControl($key);

        $this->assertFalse(count($access_controls->hashes) > 0);
        $this->assertFalse(str_contains($access_controls->string, 'RESTRICTED'));
        $this->assertFalse(str_contains($access_controls->string, 'PUBLIC_DOMAIN'));
    }
}
