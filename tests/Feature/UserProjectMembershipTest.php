<?php

namespace Tests\Feature;

use App\Models\User\ProjectMember;
use App\Models\User\Role;
use App\Models\User\User;
use App\Models\User\Project;
use App\Traits\CheckProjectMembership;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProjectMembershipTest extends TestCase
{

    use CheckProjectMembership;

    /**
     * A basic test example.
     *
     * @test
     */
    public function itReturnsTrueIfUserSharesAProjectWithADeveloper()
    {
        $developer_user = factory(User::class)->state('developer')->create();
        $project = factory(Project::class)->create();
        $role = Role::firstOrCreate(['name' => 'developer']);

        ProjectMember::create([
            'user_id'    => $developer_user->id,
            'project_id' => $project->id,
            'role_id'    => $role->id,
            'token'      => unique_random('project_members', 'token', 12)
        ]);

        $user = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'user']);

        ProjectMember::create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'role_id'    => $role->id,
            'token'      => unique_random('project_members', 'token', 12)
        ]);

        $membershipBoolean = $this->compareProjects($user->id, $developer_user->keys->first()->key);
        $this->assertTrue($membershipBoolean);
    }

    /** @test */
    public function itReturnsFalseIfUserHasNoProjects()
    {
        $developer_user = factory(User::class)->state('developer')->create();
        $project = factory(Project::class)->create();
        $role = Role::firstOrCreate(['name' => 'developer']);

        ProjectMember::create([
            'user_id'    => $developer_user->id,
            'project_id' => $project->id,
            'role_id'    => $role->id,
            'token'      => unique_random('project_members', 'token', 12)
        ]);

        $user = factory(User::class)->create();

        $membershipBoolean = $this->compareProjects($user->id, $developer_user->keys->first()->key);
        $this->assertFalse($membershipBoolean);
    }

    /** @test */
    public function itReturnsFalseIfUserAndDeveloperShareNoProjects()
    {
        $developer_user = factory(User::class)->state('developer')->create();
        $project = factory(Project::class)->create();
        $role = Role::firstOrCreate(['name' => 'developer']);

        ProjectMember::create([
            'user_id'    => $developer_user->id,
            'project_id' => $project->id,
            'role_id'    => $role->id,
            'token'      => unique_random('project_members', 'token', 12)
        ]);

        $user = factory(User::class)->create();

        $membershipBoolean = $this->compareProjects($user->id, $developer_user->keys->first()->key);
        $this->assertFalse($membershipBoolean);
    }


    // it returns false if API key User has no projects
    // it returns false if API key user and user have no shared projects
    // it returns true if API key user and user have at least one shared project

    // If user 4 has API key abc,
    // and a request is made with user 2 and API key abc,
    // the list of acceptable projects for user 2 is defined by
    // the intersection of projects where user 2 has a role-1 entry into project members for that project
    // and user 4 has a role-3 entry into project members for that project

    // An API key is "connected" to a project in this scenario:
    // There is an entry in the project_members database
    // where project_id is the project's ID and
    // user_id matches the user for that API key
    // and the role is 3
}
