<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\Language\Language;
use App\Models\User\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserSettingsController extends APIController
{

    /**
     * Display the specified Settings
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $project_id = checkParam('project_id', true);
        return UserSetting::where('user_id',$id)->where('project_id',$project_id)->first();
    }

    /**
     * Store or update newly created Settings
     *
     * @param int $id
     * @return JsonResponse
     */
    public function store($id)
    {
        $request = request()->except(['v','key']);
        $request['user_id'] = $id;
        $request['language_id'] = Language::where('id',request()->language_id)->select('id')->first()->id;

        $invalid_settings = $this->invalidSettings($request);
        if ($invalid_settings) {
            return $invalid_settings;
        }

        return UserSetting::updateOrCreate(['user_id' => $id],$request);
    }

    private function invalidSettings($request)
    {
        $validator = Validator::make($request, [
            'user_id'           => 'required|exists:dbp_users.users,id',
            'project_id'        => 'required|exists:dbp_users.projects,id',
            'language_id'       => 'nullable|exists:dbp.languages,id',
            'bible_id'          => 'nullable|exists:dbp.bibles,id',
            'book_id'           => 'nullable|exists:dbp.books,id',
            'chapter'           => 'nullable|max:150|min:1|integer',
            'theme'             => 'nullable|string',
            'preferred_font'    => 'nullable|string',
            'font_size'         => 'nullable|integer',
            'readers_mode'      => 'nullable|boolean',
            'justified_text'    => 'nullable|boolean',
            'cross_references'  => 'nullable|boolean',
            'unformatted'       => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        return false;
    }

}
