<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Mail\EmailVerification;
use App\Models\User\Key;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeysController extends APIController
{

    public function clone(Request $request)
    {
        $user = Auth::user();
        $key = Key::with('access')->where('id',$request->id)->where('user_id',$user->id)->first();
        $new_key = $key->replicate(['id']);

        return view('dashboard.keys.create');
    }

    public function edit()
    {
        return view('dashboard.keys.edit');
    }

    public function update()
    {
        return view('dashboard.keys.create');
    }

    public function access()
    {
        return view('dashboard.keys.access');
    }

    public function delete()
    {
        return view('dashboard.keys.create');
    }

    public function create()
    {
        $user = Auth::user();
        $keys = Key::with('access')->where('user_id',$user->id)->get();
        return view('dashboard.keys.create', compact('keys'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $key = unique_random('user_keys', 'key', 24);
        $user->keys()->create([
            'key'         => $key,
            'name'        => $request->name,
            'description' => $request->description,
        ]);
        $user->save();

        return view('dashboard.keys.create', compact('key'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendKeyEmail()
    {
        $user = User::firstOrNew(['email' => request()->email]);
        $user->token = unique_random('users', 'token');
        $user->save();

        \Mail::to($user)->send(new EmailVerification($user, true));
        return view('dashboard.keys.email_sent');
    }

}
