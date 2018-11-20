<?php

namespace Tests\Feature;

use App\Models\User\Project;

class v4_projectRoutesTest extends API_V4_Test
{

	public function test_v4_projects_all()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_projects.index
		 * @category Route Path: https://api.dbp.test/projects?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectsController::index
		 */
		$path = route('v4_projects.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects.store
		 * @category Route Path: https://api.dbp.test/projects?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectsController::store
		 */
		$test_project = [
			'name'            => 'Test Project Title',
			'url_avatar'      => 'example.com/avatar.jpg',
			'url_avatar_icon' => 'example.com/avatar_icon.jpg',
			'url_site'        => 'example.com',
			'description'     => '',
		];
		$path = route('v4_projects.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path,$test_project);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects.show
		 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectsController::show
		 */
		$project = Project::where('name','Test Project Title')->first();
		$path = route('v4_projects.show', array_merge(['id' => $project->id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects.update
		 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectsController::update
		 */
		$project = Project::where('name','Test Project Title')->first();
		$path = route('v4_projects.update', array_merge(['id' => $project->id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path,['description' => 'With Updated Description']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects.destroy
		 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectsController::destroy
		 */
		$path = route('v4_projects.destroy', array_merge(['id' => $project->id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}

	public function test_v4_projects_oAuthProvider_all()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_projects_oAuthProvider.index
		 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::index
		 */
        $project = Project::inRandomOrder()->first();
		$path = route('v4_projects_oAuthProvider.index', array_add($this->params,'project_id',$project->id));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects_oAuthProvider.store
		 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::store
		 */
		$project = Project::inRandomOrder()->first();
		$project_oAuth_id = rand(0,1000);
		$project_oAuth_test = [
			'project_id'       => $project->id,
			'name'             => 'test_provider',
			'id'               => $project_oAuth_id,
			'secret'           => 'eyJpdiI6InMrb2hDUVV3d0xTckRub2xPUGRFZXc92SIsInZhbHVlIjoie1JxWks4SG94YTROQVE4enA2NExLeW5ZNVE2b0lhUFZzZ1RqTDRYZCtlUW9HWEFKTFhXWTNMc2RzWE5oZTdmViIsIm1hYyI6Ijc3MmFiM2FjNWNlMGEwZWVhMDI5YmE4NTI5NjcxYTcxOGZlMjNkZmM4ODc3MzIxYzJhNTMxODc1OTljN2M1MmMifQ==',
			'client_id'        => (string) rand(0,1000),
			'client_secret'    => (string) rand(0,1000),
			'callback_url'     => 'https://listen.dbp4.org/',
			'callback_url_alt' => 'http://localhost:3000/',
			'description'      => 'Test oAuth entry'
		];
		$path = route('v4_projects_oAuthProvider.store', array_merge(['project_id' => $project->id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->post($path, $project_oAuth_test);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects_oAuthProvider.update
		 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::update
		 */
		$path = route('v4_projects_oAuthProvider.update', array_merge(['project_id' => $project->id,'id' => $project_oAuth_id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path, ['description' => 'Test oAuth updated']);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_projects_oAuthProvider.destroy
		 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::destroy
		 */
		$path = route('v4_projects_oAuthProvider.destroy', array_merge(['project_id' => $project->id,'id' => $project_oAuth_id], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}

}
