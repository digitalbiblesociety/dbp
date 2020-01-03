<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFileset;
use App\Models\User\Key;
use Auth;
use Validator;

class BibleFilesetsManagementController extends APIController
{


    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $fileset = BibleFileset::find($id);
        return view('bibles.filesets.edit', compact('fileset'));
    }

    /**
     *
     * @OA\Put(
     *     path="/bibles/filesets/{fileset_id}",
     *     tags={"Bibles"},
     *     summary="Update a bible fileset",
     *     description="Update a bible fileset",
     *     operationId="v4_bible_filesets.update",
     *     @OA\Parameter(name="fileset_id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\RequestBody(required=true, description="Fields for Bible Fileset Update",
     *          @OA\MediaType(mediaType="application/json",                  @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *          @OA\MediaType(mediaType="application/x-www-form-urlencoded", @OA\Schema(ref="#/components/schemas/BibleFileset"))
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The fileset just edited",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/BibleFileset"))
     *     )
     * )
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id)
    {
        $id    = checkParam('fileset_id', true, $id);
        $fileset = BibleFileset::find($id);

        $invalidUser = $this->validateUser($fileset);
        if ($invalidUser) {
            return $invalidUser;
        }

        $fileset->update(request()->all());
        $fileset->save();


        if ($this->api) {
            return $this->setStatusCode(201)->reply($fileset);
        }
        return view('bibles.filesets.thanks', compact('fileset'));
    }

    /**
     * Ensure the current User has permissions to alter the alphabets
     *
     * @param null $fileset
     *
     * @return \App\Models\User\User|mixed|null
     */
    private function validateUser($fileset = null)
    {
        $user = Auth::user();
        if (!$user) {
            $key = Key::where('key', $this->key)->first();
            if (!isset($key)) {
                return $this->setStatusCode(403)->replyWithError('No Authentication Provided or invalid Key');
            }
            $user = $key->user;
        }
        if (!$user->archivist && !$user->admin) {
            if ($fileset) {
                $userIsAMember = $user->organizations->where('organization_id', $fileset->organization[0]->id)->first();
                if ($userIsAMember) {
                    return false;
                }
            }
            return $this->setStatusCode(401)->replyWithError("You don't have permission to edit this filesets");
        }
        return false;
    }

    /**
     * Ensure the current fileset change is valid
     *
     * @return mixed
     */
    private function validateBibleFileset()
    {
        $validator = Validator::make(request()->all(), [
            'id'            => (request()->method() === 'POST') ? 'required|unique:bible_filesets,id|max:16|min:6' : 'required|exists:bible_filesets,id|max:16|min:6',
            'asset_id'     => 'string|max:64',
            'set_type_code' => 'string|max:16',
            'set_size_code' => 'string|max:9',
            'hidden'        => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($this->api) {
                return $this->setStatusCode(422)->replyWithError($validator->errors());
            }
            if (!$this->api) {
                return redirect('dashboard/bible-filesets/create')->withErrors($validator)->withInput();
            }
        }
        return null;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $bibles = Bible::with('currentTranslation')->select('id')->get()->pluck('currentTranslation.name', 'id');
        return view('bibles.filesets.create', compact('bibles'));
    }

    /**
     *
     * @OA\Post(
     *     path="/bibles/filesets",
     *     tags={"Bibles"},
     *     summary="Create a brand new Fileset",
     *     description="Create a new Bible Fileset",
     *     operationId="v4_bible_filesets.store",
     *     @OA\RequestBody(required=true, description="Fields for Bible Fileset Creation",
     *          @OA\MediaType(mediaType="application/json",                  @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *          @OA\MediaType(mediaType="application/x-www-form-urlencoded", @OA\Schema(ref="#/components/schemas/BibleFileset"))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The completed fileset",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/BibleFileset")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/BibleFileset"))
     *     )
     * )
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function store()
    {
        $invalidUser = $this->validateUser();
        if ($invalidUser) {
            return $invalidUser;
        }

        $invalid = $this->validateBibleFileset();
        if ($invalid) {
            return $invalid;
        }

        $fileset = BibleFileset::create(request()->all());

        // $bible = request()->file('file');

        // ProcessBible::dispatch($request->file('zip'), $fileset->id);
        if ($this->api) {
            return $this->setStatusCode(201)->reply($fileset);
        }
        return view('bibles.filesets.thanks', compact('fileset'));
    }
}
