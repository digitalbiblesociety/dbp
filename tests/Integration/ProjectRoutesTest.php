<?php

namespace Tests\Integration;

use App\Models\User\Project;

class ProjectRoutesTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_projects
     * @category Route Path: https://api.dbp.test/projects?v=4&key={key}
     * @see      \App\Http\Controllers\Organization\ProjectsController
     * @group    V4
     * @test
     */
    public function projects()
    {
        $path = route('v4_projects.index', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $test_project = [
            'name'            => 'Test Project Title',
            'url_avatar'      => 'example.com/avatar.jpg',
            'url_avatar_icon' => 'example.com/avatar_icon.jpg',
            'url_site'        => 'example.com',
            'description'     => '',
        ];
        $path = route('v4_projects.store', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->post($path, $test_project);
        $response->assertSuccessful();

        $project = Project::where('name', 'Test Project Title')->first();
        $path = route('v4_projects.show', array_merge(['id' => $project->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $project = Project::where('name', 'Test Project Title')->first();
        $path = route('v4_projects.update', array_merge(['id' => $project->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->put($path, ['description' => 'With Updated Description']);
        $response->assertSuccessful();

        $path = route('v4_projects.destroy', array_merge(['id' => $project->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->delete($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_oAuth
     * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/?v=4&key={key}
     * @see      \App\Http\Controllers\Organization\OAuthProvidersController
     * @group    V4
     * @test
     */
    public function projectsOAuthProvider()
    {
        \DB::connection('dbp_users')->table('projects')->where('name', 'Test Project Title')->delete();

        $test_project = [
            'name'            => 'Test Project Title',
            'url_avatar'      => 'example.com/avatar.jpg',
            'url_avatar_icon' => 'example.com/avatar_icon.jpg',
            'url_site'        => 'example.com',
            'description'     => '',
        ];
        $path = route('v4_projects.store', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->post($path, $test_project);
        $project = Project::where('name', 'Test Project Title')->first();

        $response->assertSuccessful();

        $path = route('v4_oAuth.index', array_add($this->params, 'project_id', $project->id));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        $project_oAuth_id = random_int(0, 1000);
        $project_oAuth_test = [
            'project_id'       => $project->id,
            'name'             => 'test_provider',
            'id'               => $project_oAuth_id,
            'secret'           => 'eyJpdiI6InMrb2hDUVV3d0xTckRub2xPUGRFZXc92SIsInZhbHVlIjoie1JxWks4SG94YTROQVE4enA2NExLeW5ZNVE2b0lhUFZzZ1RqTDRYZCtlUW9HWEFKTFhXWTNMc2RzWE5oZTdmViIsIm1hYyI6Ijc3MmFiM2FjNWNlMGEwZWVhMDI5YmE4NTI5NjcxYTcxOGZlMjNkZmM4ODc3MzIxYzJhNTMxODc1OTljN2M1MmMifQ==',
            'client_id'        => (string) random_int(0, 1000),
            'client_secret'    => (string) random_int(0, 1000),
            'callback_url'     => 'https://listen.dbp4.org/',
            'description'      => 'Test oAuth entry'
        ];
        $path = route('v4_oAuth.store', array_merge(['project_id' => $project->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->post($path, $project_oAuth_test);
        $response->assertSuccessful();

        $path = route('v4_oAuth.update', array_merge(['project_id' => $project->id,'id' => $project_oAuth_id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->put($path, ['description' => 'Test oAuth updated']);
        $response->assertSuccessful();

        $path = route('v4_oAuth.destroy', array_merge(['project_id' => $project->id,'id' => $project_oAuth_id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->delete($path);
        $response->assertSuccessful();

        $path = route('v4_projects.destroy', array_merge(['id' => $project->id], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->delete($path);
        $response->assertSuccessful();

    }
}
