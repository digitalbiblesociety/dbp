<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Mail\EmailVerification;
use App\Models\User\Key;
use App\Models\User\User;
use Illuminate\Http\Request;

class KeyController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendKeyEmail()
    {
        $user = User::firstOrCreate(['email' => request()->email]);
        $user->email_token = unique_random('users', 'email_token');
        $user->save();

        \Mail::to(request()->email)->send(new EmailVerification($user, true));

        return view('docs.keys.email_sent');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateAPIKey($token)
    {
        $user = User::where('email_token', $token)->first();
        if (!$user) {
            return view('docs.keys.failed');
        }

        $key = unique_random('user_keys', 'key', 24);
        $user->keys()->create(['key' => $key]);
        $user->verified = true;
        $user->email_token = null;
        $user->save();

        return view('docs.keys.successfully', compact('key'));
    }
}
