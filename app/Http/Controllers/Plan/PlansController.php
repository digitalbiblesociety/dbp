<?php

namespace App\Http\Controllers\Plan;

use App\Traits\AccessControlAPI;
use App\Http\Controllers\APIController;
use App\Http\Controllers\Playlist\PlaylistsController;
use App\Models\Bible\Bible;
use App\Models\Language\Language;
use App\Models\Plan\Plan;
use App\Traits\CheckProjectMembership;
use App\Models\Plan\PlanDay;
use App\Models\Plan\UserPlan;
use App\Models\Playlist\Playlist;
use Illuminate\Http\Request;

class PlansController extends APIController
{
    use AccessControlAPI;
    use CheckProjectMembership;

    protected $days_limit = 1095;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/plans",
     *     tags={"Plans"},
     *     summary="List a user's plans",
     *     operationId="v4_plans.index",
     *     @OA\Parameter(
     *          name="featured",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Plan/properties/featured"),
     *          description="Return featured plans"
     *     ),
     *     security={{"api_token":{}}},
     *     @OA\Parameter(
     *          name="iso",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Language/properties/iso"),
     *          description="The iso code to filter plans by. For a complete list see the `iso` field in the `/languages` route"
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_dir"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_plan_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_plan_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_plan_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_plan_index"))
     *     )
     * )
     *
     *
     * @return mixed
     *
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_plan_index_detail",
     *   allOf={
     *      @OA\Schema(ref="#/components/schemas/v4_plan"),
     *   },
     *   @OA\Property(property="total_days", type="integer")
     * )
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_plan_index",
     *   description="The v4 plan index response.",
     *   title="User plans",
     *   allOf={
     *      @OA\Schema(ref="#/components/schemas/pagination"),
     *   },
     *   @OA\Property(
     *      property="data",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/v4_plan_index_detail")
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

        $featured = checkBoolean('featured') || empty($user);
        $limit        = (int) (checkParam('limit') ?? 25);
        $sort_by    = checkParam('sort_by') ?? 'name';
        $sort_dir   = checkParam('sort_dir') ?? 'asc';
        $iso = checkParam('iso');

        $language_id = cacheRemember('v4_language_id_from_iso', [$iso], now()->addDay(), function () use ($iso) {
            return optional(Language::where('iso', $iso)->select('id')->first())->id;
        });

        if ($featured) {
            $cache_params = [$featured, $limit, $sort_by, $sort_dir, $iso];
            $plans = cacheRemember('v4_plan_index', $cache_params, now()->addDay(), function () use ($featured, $limit, $sort_by, $sort_dir, $user, $language_id) {
                return $this->getPlans($featured, $limit, $sort_by, $sort_dir, $user, $language_id);
            });
            return $this->reply($plans);
        }

        return $this->reply($this->getPlans($featured, $limit, $sort_by, $sort_dir, $user, $language_id));
    }

    private function getPlans($featured, $limit, $sort_by, $sort_dir, $user, $language_id)
    {
        $plans = Plan::with('days')
            ->with('user')
            ->where('draft', 0)
            ->when($language_id, function ($q) use ($language_id) {
                $q->where('plans.language_id', $language_id);
            })
            ->when($featured || empty($user), function ($q) {
                $q->where('plans.featured', '1');
            })->unless($featured, function ($q) use ($user) {
                $q->join('user_plans', function ($join) use ($user) {
                    $join->on('user_plans.plan_id', '=', 'plans.id')->where('user_plans.user_id', $user->id);
                });
                $q->select(['plans.*', 'user_plans.start_date', 'user_plans.percentage_completed']);
            })
            ->orderBy($sort_by, $sort_dir)->paginate($limit);

        foreach ($plans as $plan) {
            $plan->total_days = sizeof($plan->days);
            unset($plan->days);
        }
        return $plans;
    }

    /**
     * Store a newly created plan in storage.
     *
     *  @OA\Post(
     *     path="/plans",
     *     tags={"Plans"},
     *     summary="Crete a plan",
     *     operationId="v4_plans.store",
     *     security={{"api_token":{}}},
     *     @OA\RequestBody(required=true, description="Fields for User Plan Creation",
     *           @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="name", ref="#/components/schemas/Plan/properties/name"),
     *                  @OA\Property(property="suggested_start_date", ref="#/components/schemas/Plan/properties/suggested_start_date"),
     *                  @OA\Property(property="days",type="integer")
     *              )
     *          )
     *     ),
     *     @OA\Response(response=200, ref="#/components/responses/plan")
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
        $days = intval(checkParam('days', true));
        $days = $days > $this->days_limit ? $this->days_limit : $days;
        $suggested_start_date = checkParam('suggested_start_date');

        $plan = Plan::create([
            'user_id'               => $user->id,
            'name'                  => $name,
            'featured'              => false,
            'suggested_start_date'  => $suggested_start_date ?? ''
        ]);

        for ($i = 0; $i < intval($days); $i++) {
            $data[] = [
                'plan_id' => $plan->id,
                'name' => 'plan_' . $plan->id,
                'user_id' => $user->id
            ];
        }
        Playlist::insert($data);
        $new_playlists = Playlist::select(['id'])
            ->where('name', 'plan_' . $plan->id)
            ->where('plan_id', $plan->id)
            ->where('user_id', $user->id)
            ->get()->pluck('id');
        $plan_days_data = $new_playlists->map(function ($item) use ($plan) {
            return [
                'plan_id'               => $plan->id,
                'playlist_id'           => $item,
            ];
        })->toArray();
        Playlist::whereIn('id', $new_playlists)->update(['name' => '', 'updated_at' => 'created_at']);
        PlanDay::insert($plan_days_data);

        UserPlan::create([
            'user_id'               => $user->id,
            'plan_id'               => $plan->id
        ]);

        $plan = $this->getPlan($plan->id, $user);
        return $this->reply($plan);
    }

    /**
     *
     * @OA\Get(
     *     path="/plans/{plan_id}",
     *     tags={"Plans"},
     *     summary="A user's plan",
     *     operationId="v4_plans.show",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(
     *          name="plan_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/User/properties/id"),
     *          description="The plan id"
     *     ),
     *     @OA\Parameter(
     *          name="show_details",
     *          in="query",
     *          @OA\Schema(type="boolean"),
     *          description="Give full details of the plan"
     *     ),
     *     @OA\Parameter(
     *          name="show_text",
     *          in="query",
     *          @OA\Schema(type="boolean"),
     *          description="Enable the full details of the plan and retrieve the text of the playlists items"
     *     ),
     *     @OA\Response(response=200, ref="#/components/responses/plan")
     * )
     *
     * @param $plan_id
     *
     * @return mixed
     *
     *
     */
    public function show(Request $request, $plan_id)
    {
        $user = $request->user();

        // Validate Project / User Connection
        if (!empty($user) && !$this->compareProjects($user->id, $this->key)) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = $this->getPlan($plan_id, $user);

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $show_details = checkBoolean('show_details');
        $show_text = checkBoolean('show_text');
        if ($show_text) {
            $show_details = $show_text;
        }

        $playlist_controller = new PlaylistsController();
        if ($show_details) {
            foreach ($plan->days as $day) {
                $day_playlist = $playlist_controller->getPlaylist($user, $day->playlist_id);
                $day_playlist->path = route('v4_playlists.hls', ['playlist_id'  => $day_playlist->id, 'v' => $this->v, 'key' => $this->key]);
                $day->playlist = $day_playlist;
            }
        }

        return $this->reply($plan);
    }

    /**
     * Update the specified plan.
     *
     *  @OA\Put(
     *     path="/plans/{plan_id}",
     *     tags={"Plans"},
     *     summary="Update a plan",
     *     operationId="v4_plans.update",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
     *     @OA\Parameter(name="days", in="query",@OA\Schema(type="string"), description="Comma-separated ids of the days to be sorted or deleted"),
     *     @OA\Parameter(name="delete_days", in="query",@OA\Schema(type="boolean"), description="Will delete all days"),
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="name", ref="#/components/schemas/Plan/properties/name"),
     *              @OA\Property(property="suggested_start_date", ref="#/components/schemas/Plan/properties/suggested_start_date")
     *          )
     *     )),
     *     @OA\Response(response=200, ref="#/components/responses/plan")
     * )
     *
     * @param  int $plan_id
     * @param  string $days
     *
     * @return array|\Illuminate\Http\Response
     */
    public function update(Request $request, $plan_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('user_id', $user->id)->where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $update_values = [];

        $name = checkParam('name');
        if ($name) {
            $update_values['name'] = $name;
        }

        $suggested_start_date = checkParam('suggested_start_date');
        if ($suggested_start_date) {
            $update_values['suggested_start_date'] = $suggested_start_date;
        }

        $plan->update($update_values);

        $days = checkParam('days');
        $delete_days = checkBoolean('delete_days');

        if ($days || $delete_days) {
            $days_ids = [];
            if (!$delete_days) {
                $days_ids = explode(',', $days);
                PlanDay::setNewOrder($days_ids);
            }
            $deleted_days = PlanDay::whereNotIn('id', $days_ids)->where('plan_id', $plan->id);
            $playlists_ids = $deleted_days->pluck('playlist_id')->unique();
            $playlists = Playlist::whereIn('id', $playlists_ids);
            $deleted_days->delete();
            $playlists->delete();
        }

        $plan = $this->getPlan($plan->id, $user);

        return $this->reply($plan);
    }

    /**
     * Remove the specified plan.
     *
     *  @OA\Delete(
     *     path="/plans/{plan_id}",
     *     tags={"Plans"},
     *     summary="Delete a plan",
     *     operationId="v4_plans.destroy",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
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
     * @param  int $plan_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function destroy(Request $request, $plan_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('user_id', $user->id)->where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $playlists_ids = $plan->days()->pluck('playlist_id')->unique();
        $playlists = Playlist::whereIn('id', $playlists_ids);
        $playlists->delete();
        $user_plans = UserPlan::where('plan_id', $plan_id);
        $user_plans->delete();
        $plan->days()->delete();
        $plan->delete();

        return $this->reply('Plan Deleted');
    }

    private function validatePlan()
    {
        $validator = Validator::make(request()->all(), [
            'name'              => 'required|string'
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        return true;
    }

    /**
     * Start the specified plan.
     *
     *  @OA\Post(
     *     path="/plans/{plan_id}/start",
     *     tags={"Plans"},
     *     summary="Start a plan",
     *     operationId="v4_plans.start",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="start_date", ref="#/components/schemas/UserPlan/properties/start_date")
     *          )
     *     )),
     *     @OA\Response(response=200, ref="#/components/responses/plan")
     * )
     *
     * @param  int $plan_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function start(Request $request, $plan_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $start_date = checkParam('start_date', true);

        $user_plan = UserPlan::where('plan_id', $plan_id)->where('user_id', $user->id)->first();

        if (!$user_plan) {
            $user_plan = UserPlan::create([
                'user_id'               => $user->id,
                'plan_id'               => $plan->id
            ]);
        }

        $user_plan->start_date = $start_date;
        $user_plan->save();


        $plan = $this->getPlan($plan_id, $user);

        return $this->reply($plan);
    }

    /**
     * Store the newly created plan days.
     *
     *  @OA\Post(
     *     path="/plans/{plan_id}/day",
     *     tags={"Plans"},
     *     summary="Create plan days",
     *     operationId="v4_plans_days.store",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
     *     @OA\Parameter(name="days", in="query", required=true, @OA\Schema(type="integer"), description="Number of days to add to the plan"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_plans_days")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_plans_days")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_plans_days")),
     *         @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_plans_days"))
     *     )
     * )
     *
     * @OA\Schema (
     *   type="array",
     *   schema="v4_plans_days",
     *   title="User created plan days",
     *   description="The v4 plan days creation response.",
     *   @OA\Items(ref="#/components/schemas/PlanDay")
     * )
     * @return mixed
     */
    public function storeDay(Request $request, $plan_id)
    {
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('user_id', $user->id)->where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $days = intval(checkParam('days', true));
        $current_days_size = sizeof($plan->days);
        $total_days = $current_days_size + $days;
        $days = $total_days > $this->days_limit ? $this->days_limit - $current_days_size : $days;


        $created_plan_days = [];

        $data = [];
        for ($i = 0; $i < intval($days); $i++) {
            $data[] = [
                'plan_id' => $plan->id,
                'name' => 'plan_' . $plan->id,
                'user_id' => $user->id
            ];
        }
        Playlist::insert($data);
        $new_playlists = Playlist::select(['id'])
            ->where('name', 'plan_' . $plan->id)
            ->where('plan_id', $plan->id)
            ->where('user_id', $user->id)
            ->get()->pluck('id');
        $plan_days_data = $new_playlists->map(function ($item) use ($plan) {
            return [
                'plan_id'               => $plan->id,
                'playlist_id'           => $item,
            ];
        })->toArray();
        Playlist::whereIn('id', $new_playlists)->update(['name' => '', 'updated_at' => 'created_at']);
        PlanDay::insert($plan_days_data);


        $created_plan_days = PlanDay::where('plan_id', $plan->id)->whereIn('playlist_id', $new_playlists)->get();

        return $this->reply($created_plan_days);
    }

    /**
     * Complete a plan day.
     *
     *  @OA\Post(
     *     path="/plans/day/{day_id}/complete",
     *     tags={"Plans"},
     *     summary="Complete a plan day",
     *     operationId="v4_plans_days.complete",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="day_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/PlanDay/properties/id")),
     *     @OA\Parameter(name="complete", in="query", @OA\Schema(type="boolean")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_complete_day")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_complete_day")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_complete_day")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_complete_day"))
     *     )
     * )
     *
     * @OA\Schema (
     *   schema="v4_complete_day",
     *   description="The v4 plan day complete response",
     *   @OA\Property(property="message", type="string"),
     *   @OA\Property(property="percentage_completed", ref="#/components/schemas/UserPlan/properties/percentage_completed")
     * )
     * @return mixed
     */
    public function completeDay(Request $request, $day_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan_day = PlanDay::where('id', $day_id)->first();

        if (!$plan_day) {
            return $this->setStatusCode(404)->replyWithError('Plan Day Not Found');
        }

        $user_plan = UserPlan::join('plans', function ($join) use ($user) {
            $join->on('user_plans.plan_id', '=', 'plans.id')->where('user_plans.user_id', $user->id);
        })->where('user_plans.plan_id', $plan_day->plan_id)
            ->select('user_plans.*')
            ->first();

        if (!$user_plan) {
            return $this->setStatusCode(404)->replyWithError('User Plan Not Found');
        }

        $complete = checkParam('complete') ?? true;
        $complete = $complete && $complete !== 'false';

        if ($complete) {
            $plan_day->complete();
        } else {
            $plan_day->unComplete();
        }

        $result = $complete ? 'completed' : 'not completed';
        $user_plan->calculatePercentageCompleted()->save();

        return $this->reply([
            'percentage_completed' => $user_plan->percentage_completed,
            'message' => 'Plan Day ' . $result
        ]);
    }
    /**
     * Reset the specified plan.
     *
     *  @OA\Post(
     *     path="/plans/{plan_id}/reset",
     *     tags={"Plans"},
     *     summary="Reset a plan",
     *     description="",
     *     operationId="v4_plans.reset",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="start_date", type="string")
     *          )
     *     )),
     *     @OA\Response(response=200, ref="#/components/responses/plan")
     * )
     *
     * @param  int $plan_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function reset(Request $request, $plan_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }


        $user_plan = UserPlan::where('plan_id', $plan->id)->where('user_id', $user->id)->first();

        if (!$user_plan) {
            return $this->setStatusCode(404)->replyWithError('User Plan Not Found');
        }

        $start_date = checkParam('start_date');

        $user_plan->reset($start_date)->save();
        $plan = $this->getPlan($plan->id, $user);
        return $this->reply($plan);
    }

    /**
     * Stop the specified plan.
     *
     *  @OA\Delete(
     *     path="/plans/{plan_id}/stop",
     *     tags={"Plans"},
     *     summary="Stop a plan",
     *     description="",
     *     operationId="v4_plans.stop",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
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
     * @param  int $plan_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function stop(Request $request, $plan_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $user_plan = UserPlan::where('plan_id', $plan->id)->where('user_id', $user->id)->first();

        if (!$user_plan) {
            return $this->setStatusCode(404)->replyWithError('User Plan Not Found');
        }

        $user_plan->reset();
        $user_plan->save();
        if ($user->id !== $plan->user_id) {
            $user_plan->delete();
        }

        $plan = $this->getPlan($plan->id, $user);
        $playlist_controller = new PlaylistsController();
        foreach ($plan->days as $day) {
            $day_playlist = $playlist_controller->getPlaylist($user, $day->playlist_id);
            $day_playlist->path = route('v4_playlists.hls', ['playlist_id'  => $day_playlist->id, 'v' => $this->v, 'key' => $this->key]);
            $day->playlist = $day_playlist;
        }
        return $this->reply($plan);
    }

    /**
     *  @OA\Post(
     *     path="/plans/{plan_id}/draft",
     *     tags={"Plans"},
     *     summary="Change draft status in a plan.",
     *     operationId="v4_plans.draft",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
     *     @OA\Parameter(name="draft", in="query", @OA\Schema(type="boolean")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(type="string"))
     *     )
     * )
     */
    public function draft(Request $request, $plan_id)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('user_id', $user->id)->where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $draft = checkBoolean('draft');
        $plan->draft = $draft;

        $plan->save();

        return $this->reply('Plan draft status changed');
    }
    /**
     *  @OA\Schema (
     *   type="object",
     *   schema="v4_plan",
     *   @OA\Property(property="id", ref="#/components/schemas/Plan/properties/id"),
     *   @OA\Property(property="name", ref="#/components/schemas/Plan/properties/name"),
     *   @OA\Property(property="featured", ref="#/components/schemas/Plan/properties/featured"),
     *   @OA\Property(property="thumbnail", ref="#/components/schemas/Plan/properties/thumbnail"),
     *   @OA\Property(property="suggested_start_date", ref="#/components/schemas/Plan/properties/suggested_start_date"),
     *   @OA\Property(property="created_at", ref="#/components/schemas/Plan/properties/created_at"),
     *   @OA\Property(property="updated_at", ref="#/components/schemas/Plan/properties/updated_at"),
     *   @OA\Property(property="start_date", ref="#/components/schemas/UserPlan/properties/start_date"),
     *   @OA\Property(property="percentage_completed", ref="#/components/schemas/UserPlan/properties/percentage_completed"),
     *   @OA\Property(property="user", ref="#/components/schemas/v4_plan_index_user"),
     * )
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_plan_index_user",
     *   description="The user who created the plan",
     *   @OA\Property(property="id", type="integer"),
     *   @OA\Property(property="name", type="string")
     * )
     *
     * @OA\Schema (
     *   type="object",
     *   schema="v4_plan_detail",
     *   allOf={
     *      @OA\Schema(ref="#/components/schemas/v4_plan"),
     *   },
     *   @OA\Property(property="days",type="array",@OA\Items(ref="#/components/schemas/PlanDay"))
     * )
     *
     *
     * @OA\Response(
     *   response="plan",
     *   description="Plan Object",
     *   @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_plan_detail")),
     *   @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_plan_detail")),
     *   @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_plan_detail")),
     *   @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_plan_detail"))
     * )
     */

    private function getPlan($plan_id, $user)
    {
        $select = ['plans.*'];
        if (!empty($user)) {
            $select[] = 'user_plans.start_date';
            $select[] = 'user_plans.percentage_completed';
        }
        $plan = Plan::with('days')
            ->with('user')
            ->where('plans.id', $plan_id)
            ->when(!empty($user), function ($q) use ($user) {
                $q->leftJoin('user_plans', function ($join) use ($user) {
                    $join->on('user_plans.plan_id', '=', 'plans.id')->where('user_plans.user_id', $user->id);
                });
            })->select($select)->first();

        return $plan;
    }

    /**
     *
     * @OA\Get(
     *     path="/plans/{plan_id}/translate",
     *     tags={"Plans"},
     *     summary="Translate a user's plan",
     *     operationId="v4_plans.translate",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(
     *          name="plan_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Plan/properties/id"),
     *          description="The plan id"
     *     ),
     *     @OA\Parameter(
     *          name="bible_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Bible/properties/id"),
     *          description="The id of the bible that will be used to translate the plan"
     *     ),
     *     @OA\Parameter(
     *          name="show_details",
     *          in="query",
     *          @OA\Schema(type="boolean"),
     *          description="Give full details of the translated plan"
     *     ),
     *     @OA\Response(response=200, ref="#/components/responses/plan")
     * )
     *
     * @param $plan_id
     *
     * @return mixed
     *
     *
     */
    public function translate(Request $request, $plan_id)
    {
        $user = $request->user();

        // Validate Project / User Connection
        if (!empty($user) && !$this->compareProjects($user->id, $this->key)) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $bible_id = checkParam('bible_id', true);
        $bible = Bible::whereId($bible_id)->first();

        if (!$bible) {
            return $this->setStatusCode(404)->replyWithError('Bible Not Found');
        }

        $plan = $this->getPlan($plan_id, $user);
        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $plan_data = [
            'user_id'               => $user->id,
            'name'                  => $plan->name . ': ' . $bible->language->name . ' ' . substr($bible->id, -3),
            'featured'              => false,
            'draft'                 => true,
            'suggested_start_date'  => $plan->suggested_start_date
        ];

        $new_plan = Plan::create($plan_data);
        $playlist_controller = new PlaylistsController();
        $translation_data = [];
        $translated_percentage = 0;
        foreach ($plan->days as $day) {
            $playlist = (object) $playlist_controller->translate($request, $day->playlist_id)->original;

            PlanDay::create([
                'plan_id'               => $new_plan->id,
                'playlist_id'           => $playlist->id,
            ]);
            $translation_data[] = $playlist->translation_data;
            $translated_percentage += $playlist->translated_percentage;
        }
        $translated_percentage = sizeof($plan->days) ? $translated_percentage / sizeof($plan->days) : 0;


        UserPlan::create([
            'user_id'               => $user->id,
            'plan_id'               => $new_plan->id
        ]);

        $plan = $this->show($request, $new_plan->id)->original;
        $plan['translation_data'] = $translation_data;
        $plan['translated_percentage'] = $translated_percentage;

        return $plan;
    }
}
