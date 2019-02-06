<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user() ?? $this->user;
        return view('dashboard.profiles.edit', compact('user'));
    }

    /**
     * @param Request $request
     *
     * @return Illuminate\View\View
     */
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->fill($request->except('avatar'));
        if (isset($request->avatar)) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }
        dd($user);

        $user->save();
        return view('dashboard.profiles.edit', compact('user'));
    }

}
