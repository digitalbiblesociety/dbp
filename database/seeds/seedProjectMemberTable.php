<?php

use Illuminate\Database\Seeder;
use App\Models\User\ProjectMember;
use App\Models\User\User;

class seedProjectMemberTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $users = User::select('id')->chunk(1000, function ($users) {
		    foreach($users as $user) {
			    ProjectMember::create([
				    'user_id'    => $user->id,
				    'project_id' => 52341,
				    'role_id'    => 1,
				    'subscribed' => 0
			    ]);
		    }
	    });


    }
}
