<?php

namespace App\Http\Controllers\Playlist;

use App\Traits\AccessControlAPI;
use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFile;
use App\Models\Plan\UserPlan;
use App\Models\Playlist\Playlist;
use App\Models\Playlist\PlaylistFollower;
use App\Models\Playlist\PlaylistItems;
use App\Traits\CallsBucketsTrait;
use App\Traits\CheckProjectMembership;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlaylistsController extends APIController
{
    use AccessControlAPI;
    use CheckProjectMembership;
    use CallsBucketsTrait;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/playlists",
     *     tags={"Playlists"},
     *     summary="List a user's playlists",
     *     operationId="v4_playlists.index",
     *     @OA\Parameter(
     *          name="featured",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Playlist/properties/featured"),
     *          description="Return featured playlists"
     *     ),
     *     @OA\Parameter(
     *          name="show_details",
     *          in="query",
     *          @OA\Schema(type="boolean"),
     *          description="Give full details of the playlist"
     *     ),
     *     security={{"api_token":{}}},
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_dir"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_playlist_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_playlist_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_playlist_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_playlist_index"))
     *     )
     * )
     *
     * @param $user_id
     *
     * @return mixed
     *
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_playlist_index",
     *   description="The v4 playlist index response.",
     *   title="User playlists",
     *   allOf={
     *      @OA\Schema(ref="#/components/schemas/pagination"),
     *   },
     *   @OA\Property(
     *      property="data",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/v4_playlist")
     *   )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Validate Project / User Connection
        if (!empty($user) && !$this->compareProjects($user->id, $this->key)) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $sort_by    = checkParam('sort_by') ?? 'name';
        $sort_dir   = checkParam('sort_dir') ?? 'asc';

        $featured = checkParam('featured');
        $featured = $featured && $featured != 'false' || empty($user);
        $limit    = (int) (checkParam('limit') ?? 25);

        $select = ['user_playlists.*', DB::Raw('IF(playlists_followers.user_id, true, false) as following')];

        $show_details = checkParam('show_details');
        $show_details = $show_details && $show_details != 'false';
        $playlists = Playlist::with('user')
            ->when($show_details, function ($query) {
                $query->with('items');
            })
            ->leftJoin('playlists_followers as playlists_followers', function ($join) use ($user) {
                $user_id = empty($user) ? 0 : $user->id;
                $join->on('playlists_followers.playlist_id', '=', 'user_playlists.id')->where('playlists_followers.user_id', $user_id);
            })
            ->whereNotIn('id', function ($query) {
                $query->select('playlist_id')->from('plan_days');
            })
            ->when($featured || empty($user), function ($q) {
                $q->where('user_playlists.featured', '1');
            })->unless($featured, function ($q) use ($user) {
                $q->where('user_playlists.user_id', $user->id)
                    ->orWhere('playlists_followers.user_id', $user->id);
            })
            ->select($select)
            ->orderBy($sort_by, $sort_dir)->paginate($limit);

        if ($show_details) {
            foreach ($playlists->getCollection() as $playlist) {
                $playlist->path = route('v4_playlists.hls', ['playlist_id'  => $playlist->id, 'v' => $this->v, 'key' => $this->key]);
            }
        }

        return $this->reply($playlists);
    }

    /**
     * Store a newly created playlist in storage.
     *
     * @OA\Post(
     *     path="/playlists",
     *     tags={"Playlists"},
     *     summary="Crete a playlist",
     *     operationId="v4_playlists.store",
     *     security={{"api_token":{}}},
     *     @OA\RequestBody(required=true, description="Fields for User Playlist Creation", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="name",                  ref="#/components/schemas/Playlist/properties/name"),
     *              @OA\Property(property="external_content",      ref="#/components/schemas/Playlist/properties/external_content")
     *          )
     *     )),
     *     @OA\Response(response=200, ref="#/components/responses/playlist")
     * )
     *
     * @return \Illuminate\Http\Response|array
     */
    public function store(Request $request)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $name = checkParam('name', true);
        $external_content = checkParam('external_content');

        $playlist_data = [
            'user_id'           => $user->id,
            'name'              => $name,
            'featured'          => false
        ];

        if ($external_content) {
            $playlist_data['external_content'] = $external_content;
        }

        $playlist = Playlist::create($playlist_data);
        $playlist->user;

        return $this->reply($playlist);
    }

    /**
     *
     * @OA\Get(
     *     path="/playlists/{playlist_id}",
     *     tags={"Playlists"},
     *     summary="A user's playlist",
     *     operationId="v4_playlists.show",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(
     *          name="playlist_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Playlist/properties/id"),
     *          description="The playlist id"
     *     ),
     *     @OA\Response(response=200, ref="#/components/responses/playlist")
     * )
     *
     * @param $playlist_id
     *
     * @return mixed
     *
     *
     */
    public function show(Request $request, $playlist_id)
    {
        // Fetch and Assign $_GET params
        $asset_id    = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3.bucket');

        $user = $request->user();

        // Validate Project / User Connection
        if (!empty($user) && !$this->compareProjects($user->id, $this->key)) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $playlist = $this->getPlaylist($user, $playlist_id);
        $playlist->path = route('v4_playlists.hls', ['playlist_id'  => $playlist_id, 'v' => $this->v, 'key' => $this->key]);

        if (!$playlist) {
            return $this->setStatusCode(404)->replyWithError('Playlist Not Found');
        }

        return $this->reply($playlist);
    }

    /**
     * Update the specified playlist.
     *
     * @OA\Put(
     *     path="/playlists/{playlist_id}",
     *     tags={"Playlists"},
     *     summary="Update a playlist",
     *     operationId="v4_playlist.update",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="playlist_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Playlist/properties/id")),
     *     @OA\Parameter(name="items", in="query", @OA\Schema(type="string"), description="Comma-separated ids of the playlist items to be sorted or deleted"),
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="name", ref="#/components/schemas/Playlist/properties/name"),
     *              @OA\Property(property="external_content", type="string")
     *          )
     *     )),
     *     @OA\Response(response=200, ref="#/components/responses/playlist")
     * )
     *
     * @param  int $playlist_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function update(Request $request, $playlist_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $playlist = Playlist::with('items')
            ->with('user')
            ->where('user_id', $user->id)
            ->where('id', $playlist_id)->first();

        if (!$playlist) {
            return $this->setStatusCode(404)->replyWithError('Playlist Not Found');
        }

        $update_values = [];

        $name = checkParam('name');
        if ($name) {
            $update_values['name'] = $name;
        }

        $external_content = checkParam('external_content');
        if ($external_content) {
            $update_values['external_content'] = $external_content;
        }

        $playlist->update($update_values);

        $items = checkParam('items');

        if ($items) {
            $items_ids = explode(',', $items);
            PlaylistItems::setNewOrder($items_ids);
            $deleted_items = PlaylistItems::whereNotIn('id', $items_ids)->where('playlist_id', $playlist->id);
            $deleted_items->delete();
        }

        $playlist = $this->getPlaylist($user, $playlist_id);

        return $this->reply($playlist);
    }

    /**
     * Remove the specified playlist.
     *
     * @OA\Delete(
     *     path="/playlists/{playlist_id}",
     *     tags={"Playlists"},
     *     summary="Delete a playlist",
     *     operationId="v4_playlists.destroy",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="playlist_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Playlist/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(type="string"))
     *     )
     * )
     *
     * @param  int $playlist_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function destroy(Request $request, $playlist_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $playlist = Playlist::where('user_id', $user->id)->where('id', $playlist_id)->first();

        if (!$playlist) {
            return $this->setStatusCode(404)->replyWithError('Playlist Not Found');
        }

        $playlist->delete();

        return $this->reply('Playlist Deleted');
    }

    /**
     * Follow the specified playlist.
     *
     * @OA\Post(
     *     path="/playlists/{playlist_id}/follow",
     *     tags={"Playlists"},
     *     summary="Follow a playlist",
     *     operationId="v4_playlists.start",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="playlist_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Playlist/properties/id")),
     *     @OA\Parameter(name="follow", in="query", @OA\Schema(type="boolean")),
     *     @OA\Response(response=200, ref="#/components/responses/playlist")
     * )
     *
     * @param  int $playlist_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function follow(Request $request, $playlist_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $playlist = Playlist::where('id', $playlist_id)->first();

        if (!$playlist) {
            return $this->setStatusCode(404)->replyWithError('Playlist Not Found');
        }

        $follow = checkParam('follow');
        $follow = $follow && $follow != 'false';


        if ($follow) {
            $follower = PlaylistFollower::firstOrNew([
                'user_id'               => $user->id,
                'playlist_id'               => $playlist->id
            ]);
            $follower->save();
        } else {
            $follower = PlaylistFollower::where('playlist_id', $playlist->id)
                ->where('user_id', $user->id);
            $follower->delete();
        }

        $playlist = $this->getPlaylist($user, $playlist_id);
        return $this->reply($playlist);
    }

    /**
     * Store a newly created playlist item.
     *
     * @OA\Post(
     *     path="/playlists/{playlist_id}/item",
     *     tags={"Playlists"},
     *     summary="Crete a playlist item",
     *     operationId="v4_playlists_items.store",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="playlist_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Playlist/properties/id")),
     *     @OA\RequestBody(ref="#/components/requestBodies/PlaylistItems"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_playlist_items")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_playlist_items")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_playlist_items")),
     *         @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_playlist_items"))
     *     )
     * )
     *
     * @OA\RequestBody(
     *     request="PlaylistItems",
     *     required=true,
     *     description="Fields for Playlist item creation",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *              @OA\Property(property="fileset_id", ref="#/components/schemas/PlaylistItems/properties/fileset_id"),
     *              @OA\Property(property="book_id", ref="#/components/schemas/PlaylistItems/properties/book_id"),
     *              @OA\Property(property="chapter_start", ref="#/components/schemas/PlaylistItems/properties/chapter_start"),
     *              @OA\Property(property="chapter_end", ref="#/components/schemas/PlaylistItems/properties/chapter_end"),
     *              @OA\Property(property="verse_start", ref="#/components/schemas/PlaylistItems/properties/verse_start"),
     *              @OA\Property(property="verse_end", ref="#/components/schemas/PlaylistItems/properties/verse_end")
     *         )
     *     )
     * )
     *
     * @OA\Schema (
     *   type="array",
     *   schema="v4_playlist_items",
     *   title="User created playlist items",
     *   description="The v4 playlist items creation response.",
     *   @OA\Items(ref="#/components/schemas/PlaylistItemDetail")
     * )
     * @return mixed
     */
    public function storeItem(Request $request, $playlist_id)
    {
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $playlist = Playlist::with('items')
            ->with('user')
            ->where('user_id', $user->id)
            ->where('id', $playlist_id)->first();

        if (!$playlist) {
            return $this->setStatusCode(404)->replyWithError('Playlist Not Found');
        }

        $playlist_items = json_decode($request->getContent());
        $single_item = checkParam('fileset_id');

        if ($single_item) {
            $playlist_items = [$playlist_items];
        }

        $created_playlist_items = [];

        foreach ($playlist_items as $playlist_item) {
            $verses = $playlist_items->verses ?? 0;

            $created_playlist_item = PlaylistItems::create([
                'playlist_id'       => $playlist->id,
                'fileset_id'        => $playlist_item->fileset_id,
                'book_id'           => $playlist_item->book_id,
                'chapter_start'     => $playlist_item->chapter_start,
                'chapter_end'       => $playlist_item->chapter_end,
                'verse_start'       => $playlist_item->verse_start ?? null,
                'verse_end'         => $playlist_item->verse_end ?? null,
                'verses'            => $verses
            ]);
            $created_playlist_item->calculateDuration()->save();
            if (!$verses) {
                $created_playlist_item->calculateVerses()->save();
            }
            $created_playlist_items[] = $created_playlist_item;
        }

        return $this->reply($single_item ? $created_playlist_items[0] : $created_playlist_items);
    }

    /**
     * Complete a playlist item.
     *
     * @OA\Post(
     *     path="/playlists/item/{item_id}/complete",
     *     tags={"Playlists"},
     *     summary="Complete a playlist item",
     *     operationId="v4_playlists_items.complete",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="item_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/PlaylistItems/properties/id")),
     *     @OA\Parameter(name="complete", in="query", @OA\Schema(type="boolean")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_complete_playlist_item")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_complete_playlist_item")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_complete_playlist_item")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_complete_playlist_item"))
     *     )
     * )
     *
     * @OA\Schema (
     *   schema="v4_complete_playlist_item",
     *   description="The v4 plan day complete response",
     *   @OA\Property(property="message", type="string"),
     *   @OA\Property(property="percentage_completed", ref="#/components/schemas/UserPlan/properties/percentage_completed")
     * )
     * @return mixed
     */
    public function completeItem(Request $request, $item_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $playlist_item = PlaylistItems::where('id', $item_id)->first();

        if (!$playlist_item) {
            return $this->setStatusCode(404)->replyWithError('Playlist Item Not Found');
        }

        $user_plan = UserPlan::join('plans', function ($join) use ($user) {
            $join->on('user_plans.plan_id', '=', 'plans.id')->where('user_plans.user_id', $user->id);
        })
            ->join('plan_days', function ($join) use ($playlist_item) {
                $join->on('plan_days.plan_id', '=', 'plans.id')->where('plan_days.playlist_id', $playlist_item->playlist_id);
            })
            ->select('user_plans.*')
            ->first();

        if (!$user_plan) {
            return $this->setStatusCode(404)->replyWithError('User Plan Not Found');
        }

        $complete = checkParam('complete') ?? true;
        $complete = $complete && $complete !== 'false';

        if ($complete) {
            $playlist_item->complete();
        } else {
            $playlist_item->unComplete();
        }

        $result = $complete ? 'completed' : 'not completed';
        $user_plan->calculatePercentageCompleted()->save();

        return $this->reply([
            'percentage_completed' => $user_plan->percentage_completed,
            'message' => 'Playlist Item ' . $result
        ]);
    }

    public function hls(Response $response, $playlist_id)
    {
        $download = checkParam('download');
        $download = $download && $download != 'false';
        $playlist = Playlist::with('items')->find($playlist_id);
        if (!$playlist) {
            return $this->setStatusCode(404)->replyWithError('Playlist Not Found');
        }

        $signed_files = [];
        $transaction_id = random_int(0, 10000000);
        try {
            apiLogs(request(), $response->getStatusCode(), $transaction_id);
        } catch (\Exception $e) {
            Log::error($e);
        }
        $durations = [];
        $hls_items = '';
        foreach ($playlist->items as $item) {
            $fileset = $item->fileset;
            if (!Str::contains($fileset->set_type_code, 'audio')) {
                continue;
            }
            $bible_files = BibleFile::with('streamBandwidth.transportStreamTS')->with('streamBandwidth.transportStreamBytes')->where([
                'hash_id' => $fileset->hash_id,
                'book_id' => $item->book_id,
            ])
                ->where('chapter_start', '>=', $item->chapter_start)
                ->where('chapter_start', '<=', $item->chapter_end)
                ->get();
            if ($fileset->set_type_code === 'audio_stream') {
                $result = $this->processHLSAudio($bible_files, $hls_items, $signed_files, $transaction_id, $item, $download);
                $hls_items = $result->hls_items;
                $signed_files = $result->signed_files;
                $durations[] = $this->getMaxRuntime($bible_files);
            } else {
                $result = $this->processMp3Audio($bible_files, $hls_items, $signed_files, $transaction_id, $download);
                $hls_items = $result->hls_items;
                $signed_files = $result->signed_files;
                $durations[] = $bible_files->max('duration');
            }
        }

        $current_file = "#EXTM3U\n";
        $current_file .= '#EXT-X-TARGETDURATION:' . ceil(collect($durations)->max()) . "\n";
        $current_file .= "#EXT-X-VERSION:4\n";
        $current_file .= "#EXT-X-MEDIA-SEQUENCE:0\n";
        $current_file .= '#EXT-X-ALLOW-CACHE:YES';
        $current_file .= $hls_items;
        $current_file .= "\n#EXT-X-ENDLIST";

        if ($download) {
            return $this->reply(['hls' => $current_file, 'signed_files' => $signed_files]);
        }

        return response($current_file, 200, [
            'Content-Disposition' => 'attachment; filename="' . $playlist_id . '.m3u8"',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }

    private function getMaxRuntime($bible_files)
    {
        $runtimes = [];
        foreach ($bible_files as $bible_file) {
            foreach ($bible_file->streamBandwidth as $bandwidth) {
                $runtimes[] = $bandwidth->transportStreamTS->max('runtime');
                $runtimes[] = $bandwidth->transportStreamBytes->max('runtime');
            }
        }
        return collect($runtimes)->max();
    }

    private function processHLSAudio($bible_files, $hls_items, $signed_files, $transaction_id, $item, $download)
    {
        foreach ($bible_files as $bible_file) {
            $currentBandwidth = $bible_file->streamBandwidth->first();

            $transportStream = sizeof($currentBandwidth->transportStreamBytes) ? $currentBandwidth->transportStreamBytes : $currentBandwidth->transportStreamTS;
            if ($item->verse_end && $item->verse_start) {
                $transportStream = $this->processVersesOnTransportStream($item, $transportStream, $bible_file);
            }

            $fileset = $bible_file->fileset;

            foreach ($transportStream as $stream) {
                $hls_items .= "\n#EXTINF:$stream->runtime," . $item->id;
                if (isset($stream->timestamp)) {
                    $hls_items .= "\n#EXT-X-BYTERANGE:$stream->bytes@$stream->offset";
                    $fileset = $stream->timestamp->bibleFile->fileset;
                    $stream->file_name = $stream->timestamp->bibleFile->file_name;
                }
                $bible_path = $bible_file->fileset->bible->first()->id;
                $file_path = 'audio/' . $bible_path . '/' . $fileset->id . '/' . $stream->file_name;
                if (!isset($signed_files[$file_path])) {
                    $signed_files[$file_path] = $this->signedUrl($file_path, $fileset->asset_id, $transaction_id);
                }
                $hls_file_path = $download ? $file_path : $signed_files[$file_path];
                $hls_items .= "\n" . $hls_file_path;
            }
            $hls_items .= "\n" . '#EXT-X-DISCONTINUITY';
        }

        return (object) ['hls_items' => $hls_items, 'signed_files' => $signed_files];
    }

    private function processMp3Audio($bible_files, $hls_items, $signed_files, $transaction_id, $download)
    {
        foreach ($bible_files as $bible_file) {
            $default_duration = $bible_file->duration ?? 180;
            $hls_items .= "\n#EXTINF:$default_duration,";

            $bible_path = $bible_file->fileset->bible->first()->id;
            $file_path = 'audio/' . $bible_path . '/' . $bible_file->fileset->id . '/' . $bible_file->file_name;
            $hls_items .= "\n";
            if (!isset($signed_files[$file_path])) {
                $signed_files[$file_path] = $this->signedUrl($file_path, $bible_file->fileset->asset_id, $transaction_id);
            }
            $hls_file_path = $download ? $file_path : $signed_files[$file_path];
            $hls_items .= "\n" . $hls_file_path;
            $hls_items .= "\n" . '#EXT-X-DISCONTINUITY';
        }

        return (object) ['hls_items' => $hls_items, 'signed_files' => $signed_files];
    }

    private function processVersesOnTransportStream($item, $transportStream, $bible_file)
    {
        if ($item->chapter_end  === $item->chapter_start) {
            $transportStream = $transportStream->splice(1, $item->verse_end)->all();
            return collect($transportStream)->slice($item->verse_start - 1)->all();
        }

        $transportStream = $transportStream->splice(1)->all();
        if ($bible_file->chapter_start === $item->chapter_start) {
            return collect($transportStream)->slice($item->verse_start - 1)->all();
        }
        if ($bible_file->chapter_start === $item->chapter_end) {
            return collect($transportStream)->splice(0, $item->verse_end)->all();
        }

        return $transportStream;
    }

    /**
     * @OA\Schema (
     *   type="object",
     *   schema="PlaylistItemDetail",
     *   @OA\Property(property="id", ref="#/components/schemas/PlaylistItems/properties/id"),
     *   @OA\Property(property="bible_id", ref="#/components/schemas/Bible/properties/id"),
     *   @OA\Property(property="fileset_id", ref="#/components/schemas/PlaylistItems/properties/fileset_id"),
     *   @OA\Property(property="book_id", ref="#/components/schemas/PlaylistItems/properties/book_id"),
     *   @OA\Property(property="chapter_start", ref="#/components/schemas/PlaylistItems/properties/chapter_start"),
     *   @OA\Property(property="chapter_end", ref="#/components/schemas/PlaylistItems/properties/chapter_end"),
     *   @OA\Property(property="verse_start", ref="#/components/schemas/PlaylistItems/properties/verse_start"),
     *   @OA\Property(property="verse_end", ref="#/components/schemas/PlaylistItems/properties/verse_end"),
     *   @OA\Property(property="duration", ref="#/components/schemas/PlaylistItems/properties/duration"),
     *   @OA\Property(property="completed", ref="#/components/schemas/PlaylistItems/properties/completed")
     * )
     * @OA\Schema (
     *   type="object",
     *   schema="v4_playlist",
     *   @OA\Property(property="id", ref="#/components/schemas/Playlist/properties/id"),
     *   @OA\Property(property="name", ref="#/components/schemas/Playlist/properties/name"),
     *   @OA\Property(property="featured", ref="#/components/schemas/Playlist/properties/featured"),
     *   @OA\Property(property="created_at", ref="#/components/schemas/Playlist/properties/created_at"),
     *   @OA\Property(property="updated_at", ref="#/components/schemas/Playlist/properties/updated_at"),
     *   @OA\Property(property="external_content", ref="#/components/schemas/Playlist/properties/external_content"),
     *   @OA\Property(property="following", ref="#/components/schemas/Playlist/properties/following"),
     *   @OA\Property(property="user", ref="#/components/schemas/v4_playlist_index_user"),
     * )
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_playlist_index_user",
     *   description="The user who created the playlist",
     *   @OA\Property(property="id", type="integer"),
     *   @OA\Property(property="name", type="string")
     * )
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_playlist_detail",
     *   allOf={
     *      @OA\Schema(ref="#/components/schemas/v4_playlist"),
     *   },
     *   @OA\Property(property="items",type="array",@OA\Items(ref="#/components/schemas/PlaylistItemDetail"))
     * )
     *
     * @OA\Response(
     *   response="playlist",
     *   description="Playlist Object",
     *   @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_playlist_detail")),
     *   @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_playlist_detail")),
     *   @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_playlist_detail")),
     *   @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_playlist_detail"))
     * )
     */

    public function getPlaylist($user, $playlist_id)
    {
        $select = ['user_playlists.*', DB::Raw('IF(playlists_followers.user_id, true, false) as following')];
        $playlist = Playlist::with('items')
            ->with('user')
            ->leftJoin('playlists_followers as playlists_followers', function ($join) use ($user) {
                $user_id = empty($user) ? 0 : $user->id;
                $join->on('playlists_followers.playlist_id', '=', 'user_playlists.id')->where('playlists_followers.user_id', $user_id);
            })
            ->where('user_playlists.id', $playlist_id)
            ->select($select)
            ->first();

        $playlist->items = $playlist->items->map(function ($item) {
            $bible = $item->fileset->bible->first();
            if ($bible) {
                $item->bible_id = $bible->id;
            }
            unset($item->fileset);
            return $item;
        });

        return $playlist;
    }
}
