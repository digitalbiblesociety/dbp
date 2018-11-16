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
        $developer = Key::with(['user.projectMembers.role' => function ($query) {
            $query->whereIn('name', ['admin','developer']);
        }])->where('key', $key)->first();

        if (!$developer->user->projectMembers) {
            return false;
        }

        $developer_projects = $developer->user->projectMembers->pluck('project_id')->toArray();
        $user = User::where('id', $user_id)->first();

        if (!$user) {
            return false;
        }

        $user_projects = $user->projectMembers->pluck('project_id')->toArray();
        $membership = \count(array_intersect($developer_projects, $user_projects));

        return !$membership ? false : true;
    }
}
