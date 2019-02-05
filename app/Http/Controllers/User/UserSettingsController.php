<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\UserSetting;
use Illuminate\Http\Request;

class UserSettingsController extends APIController
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return UserSetting::find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return UserSetting::create($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return UserSetting::where('user_id',$id)->update($request->all());
    }

    private function validateSettings()
    {
        $validator = Validator::make(request()->all(), [
            'user_id'           => 'required|exists:dbp_users.users,id',
            'bible_id'          => 'exists:dbp.bibles,id',
            'book_id'           => 'exists:dbp.books,id',
            'chapter'           => 'max:150|min:1|integer',
            'font_size'         => 'integer',
            'justified_text'    => 'boolean',
            'theme'             => 'string|nullable',
            'preferred_font'    => 'string|nullable',
            'readers_mode'      => 'boolean',
            'justified_text'    => 'boolean',
            'cross_references'  => 'boolean',
            'unformatted'       => 'boolean',
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        return true;
    }

}
