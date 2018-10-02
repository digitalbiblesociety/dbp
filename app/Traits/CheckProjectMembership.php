<?php

namespace App\Traits;

use App\Models\User\Key;
use App\Models\User\User;

trait CheckProjectMembership
{

	public function compareProjects($user_id)
	{
		$developer = Key::where('key',$this->key)->first()->user;
		$developer_projects = $developer->projectDeveloper->pluck('project_id')->toArray();
		$user_projects = User::where('id',$user_id)->first()->projectMembers->pluck('project_id')->toArray();

		$membership = count(array_intersect($developer_projects,$user_projects));
		if(!$membership) return false;
		return true;
	}

}