<?php

namespace App\Policies;

use App\Models\User\User;
use App\Models\Bible\BibleFileset;
use Illuminate\Auth\Access\HandlesAuthorization;

class BibleFilesetsPolicy
{
    use HandlesAuthorization;

	/**
     * Determine whether the user can view the bibleFileset.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Bible\BibleFileset  $bibleFileset
     * @return mixed
     */
    public function view(User $user, BibleFileset $bibleFileset)
    {
	    if(!$user->organizations) return false;
        return $bibleFileset->hidden OR $user->authorizedArchivist($bibleFileset->organization_id);
    }

    /**
     * Determine whether the user can create bibleFilesets.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
	    return $user->archivist;
    }

    /**
     * Determine whether the user can update the bibleFileset.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Bible\BibleFileset  $bibleFileset
     * @return mixed
     */
    public function update(User $user, BibleFileset $bibleFileset)
    {
    	$verified = false;
    	if(!$user->organizations) return false;
    	return $user->authorizedArchivist($bibleFileset->organization_id);
    }

    /**
     * Determine whether the user can delete the bibleFileset.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Bible\BibleFileset  $bibleFileset
     * @return mixed
     */
    public function delete(User $user, BibleFileset $bibleFileset)
    {
	    if(!$user->organizations) return false;
	    return $user->authorizedArchivist($bibleFileset->organization_id);
    }


}
