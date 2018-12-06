<?php

namespace Tests\Feature;

use App\Models\User\AccessGroup;
use App\Models\User\AccessType;
use App\Models\User\Key;
use App\Models\User\User;
use App\Traits\AccessControlAPI;
use Mockery as m;
use Torann\GeoIP\GeoIP;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AccessControlTest extends ApiV4Test
{
    use AccessControlAPI;
    use RefreshDatabase;

    /**
     *
     * @group v4_access
     * @test
     */
    public function accessAllowedBasic()
    {
        $user = $this->createUserAndAccessGroup(factory(AccessType::class)->make());
        $access_controls = $this->accessControl($user->keys->first());

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
        // Mock IP address uses North America and US by default
        $this->mockIpTest();

        $user = $this->createUserAndAccessGroup(factory(AccessType::class)->make(['country_id' => 'US']));
        $access_controls = $this->accessControl($user->keys->first());

        // Assert hashes attached to the group are returned
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
        // Mock IP address uses North America and US by default
        $this->mockIpTest();

        $user = $this->createUserAndAccessGroup(factory(AccessType::class)->make(['country_id' => 'IN']));
        $access_controls = $this->accessControl($user->keys->first());

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
        // Mock IP Test asserts North American Origin by Default
        $this->mockIpTest();

        $user = $this->createUserAndAccessGroup(factory(AccessType::class)->make(['country_id' => 'US']));
        $access_controls = $this->accessControl($user->keys->first());

        $this->assertNotContains($user->keys->access->first()->filesets->hash_id, $access_controls->hashes);
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


    private function createUserAndAccessGroup($type)
    {
        $user = factory(User::class)->state('developer')->create();
        $user->keys->first()->access()->sync(factory(AccessGroup::class)->create()
            ->each(function ($access_group) use ($type) {
                $access_group->types()->save($type);
            }));

        return $user;
    }

    private function mockIpTest()
    {
        $geoipMock = m::mock(GeoIP::class);
        $geoipMock->shouldReceive('getLocation')->andReturn((object) ['continent' => 'NA', 'iso_code' => 'US']);
        $this->app->instance('geoip', $geoipMock);
    }

}
