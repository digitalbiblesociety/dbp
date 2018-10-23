<?php

namespace App\Traits;

use App\Models\User\Key;
use App\Models\User\User;

trait CheckProjectMembership
{

	/**
	 *
	 * Compares current API developers key with a user to
	 * see if the developer manages a project that the
	 * user has been connected to in some happy way
	 *
	 * @param int $user_id
	 * @param string $key
	 *
	 * @return bool
	 */
	public function compareProjects($user_id, $key)
	{
		$developer = Key::with(['user.projectMembers' => function ($query) {
			$query->whereIn('slug', ['admin','developer']);
		}])->where('key',$key)->first();
		$developer_projects = $developer->user->projectMembers->where('role','!=',1)->pluck('project_id')->toArray();
		$user_projects = User::where('id',$user_id)->first()->projectMembers->pluck('project_id')->toArray();

		$membership = \count(array_intersect($developer_projects,$user_projects));
		if(!$membership) return false;
		return true;
	}

}