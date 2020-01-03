<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Models\User\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeysController extends APIController
{
    public function clone(Request $request)
    {
        $user = Auth::user();
        $key = Key::with('access')->where('id', $request->id)->where('user_id', $user->id)->first();

        $new_key = $key->replicate(['id']);
        $new_key->key = unique_random('user_keys', 'key', 24);
        $new_key->save();

        return view('dashboard.keys.create');
    }

    public function create()
    {
        return view('dashboard.keys.create');
    }

    public function delete($id)
    {
        $key = Key::where('id', $id)->first();
        return view('dashboard.keys.delete', compact('key'));
    }

    public function destroy($id)
    {
        $key = Key::where('id', $id)->where('user_id', Auth::user()->id)->first();
        $key->delete();

        return view('dashboard.keys.create');
    }

    public function store(Request $request)
    {
        Auth::user()->keys()->create([
            'key'         => unique_random('user_keys', 'key', 24),
            'name'        => $request->name,
            'description' => $request->description,
        ]);
        return view('dashboard.keys.create');
    }

    public function accessGroups($id)
    {
        $key = Key::where('id', $id)->where('user_id', Auth::user()->id)->first();
        return view('dashboard.keys.access', compact('key'));
    }

    public function accessGroup($id, $access_group_id)
    {
    }
}
