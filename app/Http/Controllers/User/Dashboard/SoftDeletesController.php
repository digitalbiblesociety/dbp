<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Models\User\User;
use jeremykenedy\LaravelRoles\Models\Role;

class SoftDeletesController extends APIController
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Get Soft Deleted User.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedUser($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->first();
        if (!$user) return redirect('/users/deleted/')->with('error', trans('usersmanagement.errorUserNotFound'));
        return $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::onlyTrashed()->get();
        $roles = Role::all();

        return view('usersmanagement.show-deleted-users', compact('users', 'roles'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = self::getDeletedUser($id);

        return view('usersmanagement.show-deleted-user')->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $user = self::getDeletedUser($id);
        $user->restore();

        return redirect('/users/')->with('success', trans('usersmanagement.successRestore'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = self::getDeletedUser($id);
        $user->forceDelete();

        return redirect('/users/deleted/')->with('success', trans('usersmanagement.successDestroy'));
    }
}
