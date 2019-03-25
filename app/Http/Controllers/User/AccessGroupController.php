<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\AccessGroup;
use App\Models\User\Key;
use App\Traits\AccessControlAPI;
use App\Transformers\AccessGroupTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccessGroupController extends APIController
{

    use AccessControlAPI;


    public function index()
    {
        $cache_string = 'access_groups';
        $access_groups = \Cache::remember($cache_string, now()->addDay(), function () {
            $access_groups = AccessGroup::select(['id','name'])->get();
            return $access_groups->pluck('name', 'id');
        });

        return $this->reply($access_groups);
    }

    public function store(Request $request)
    {
        $invalidUser = $this->validateUser();
        if ($invalidUser) {
            return $invalidUser;
        }

        $invalid = $this->validateAccessGroup($request);
        if ($invalid) {
            return $this->setStatusCode(400)->reply($invalid);
        }

        $access_group = \DB::transaction(function () use ($request) {
            $access_group = AccessGroup::create($request->only(['name','description']));
            if ($request->filesets) {
                foreach ($request->filesets as $fileset) {
                    $access_group->filesets()->create(['hash_id' => $fileset]);
                }
                foreach ($request->users as $user) {
                    $access_group->users()->create(['user_id' => $user]);
                }
            }
            return $access_group;
        });

        if (!$this->api) {
            return redirect()->route('access.groups.show', ['group_id' => $access_group->id]);
        }
        return $this->reply($access_group);
    }


    public function show($id)
    {
        $cache_string = 'access_group:'.strtolower($id);
        $access_group = \Cache::remember($cache_string, now()->addDay(), function () use ($id) {

            $access_group = AccessGroup::with('filesets', 'types', 'keys')->findByIdOrName($id)->first();
            if (!$access_group) {
                return $this->setStatusCode(404)->replyWithError(trans('api.access_group_404'));
            }
            $access_group->current_key = $this->key;
            return fractal($access_group, new AccessGroupTransformer());
        });

        return $this->reply($access_group);
    }

    public function current()
    {
        $cache_string = 'access_current:'.$this->key;
        $current_access = \Cache::remember($cache_string, now()->addDay(), function() {
            $current_access = $this->accessControl($this->key);
            $current_access->hash_count = \count($current_access->hashes);
            return $current_access;
        });

        return $this->reply($current_access);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invalid = $this->validateAccessGroup($request);
        if ($invalid) {
            return $this->setStatusCode(400)->reply($invalid);
        }

        $access_group = AccessGroup::where('id', $id)->orWhere('name', $id)->first();
        if (!$access_group) {
            return $this->setStatusCode(404)->replyWithError(trans('api.'));
        }
        $access_group->fill($request->all())->save();

        if (isset($request->filesets)) {
            $access_group->filesets()->createMany($request->filesets);
        }
        if (isset($request->keys)) {
            $access_group->keys()->createMany($request->keys);
        }
        if (isset($request->types)) {
            $access_group->keys()->sync($request->types);
        }

        return $this->reply($access_group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $invalidUser = $this->validateUser();
        if ($invalidUser) {
            return $invalidUser;
        }

        $access_group = AccessGroup::where('id', $id)->orWhere('name', $id)->first();
        if (!$access_group) {
            return $this->setStatusCode(404)->replyWithError('Access Group not Found');
        }
        $access_group->delete();

        return $this->reply('successfully deleted');
    }

    /**
     * Ensure the current access_group change is valid
     *
     * @param Request $request
     * @return mixed
     */
    private function validateAccessGroup(Request $request)
    {
        $request_is_post   = $request->method() === 'POST';
        $require_condition = $request_is_post ? 'required|' : '';
        $unique_condition  = $request_is_post ? 'unique' : 'exists';

        $validator = \Validator::make($request->all(), [
            'name'               => $require_condition.'max:64|alpha_dash|'.$unique_condition.':dbp.access_groups,name',
            'description'        => 'string',
            'filesets.*'         => 'exists:dbp.bible_filesets,hash_id',
            'keys.*'             => 'exists:dbp_users.user_keys,key',
            'types.*'            => 'exists:dbp.access_types,id',
        ]);

        if ($validator->fails()) {
            if (!$this->api) {
                return redirect('access/groups/create')->withErrors($validator)->withInput();
            }
            return $this->setStatusCode(422)->replyWithError($validator->errors());
        }
        return false;
    }

    private function validateUser()
    {
        $is_admin = $this->user->roles->where('slug', 'admin')->first();
        if ($is_admin) {
            return true;
        }
        return null;
    }
}
