<?php

namespace App\Http\Controllers\Plan;

use App\Traits\AccessControlAPI;
use App\Http\Controllers\APIController;
use App\Models\User\User;
use App\Models\Plan\Plan;
use App\Traits\CheckProjectMembership;
use App\Models\Plan\PlanDay;
use App\Models\Plan\UserPlan;

class PlansController extends APIController
{
    use AccessControlAPI;
    use CheckProjectMembership;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/plans/{user_id}",
     *     tags={"Plans"},
     *     summary="List a user's plans",
     *     description="",
     *     operationId="v4_plans.index",
     *     @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/User/properties/id"),
     *          description="The user who created the plans"
     *     ),
     *     @OA\Parameter(
     *          name="featured",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Plan/properties/featured"),
     *          description="Return featured plans"
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
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
     * @param $user_id
     *
     * @return mixed
     * 
     * 
     * @OA\Schema (
     *   type="array",
     *   schema="v4_plan_index",
     *   description="The v4 plan index response.",
     *   title="User plan",
     *   @OA\Xml(name="v4_plan_index"),
     *   @OA\Items(ref="#/components/schemas/Plan")
     * )
     */
    public function index($user_id)
    {

        // Validate Project / User Connection
        $user = User::where('id', $user_id)->select('id')->first();

        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404'));
        }

        $user_is_member = $this->compareProjects($user_id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $featured = checkParam('featured');
        $featured = $featured && $featured != 'false';

        $plans = Plan::with('days')
            ->with('user')
            ->when($featured, function ($q) {
                $q->where('plans.featured', '1');
            })->unless($featured, function ($q) {
                $q->rightJoin('user_plans', function ($join) {
                    $join->on('user_plans.user_id', '=', 'plans.user_id')->on('user_plans.plan_id', '=', 'plans.id');
                });
            })->orderBy('plans.updated_at', 'desc')->get();

        return $this->reply($plans);
    }

    /**
     * Store a newly created plan in storage.
     *
     * @OA\Post(
     *     path="/plans/{user_id}",
     *     tags={"Plans"},
     *     summary="Crete a plan",
     *     description="",
     *     operationId="v4_plans.store",
     *     @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/User/properties/id"),
     *          description="The user who is creating the plan"
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\RequestBody(required=true, description="Fields for User Plan Creation",
     *           @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="name", ref="#/components/schemas/Plan/properties/name"),
     *                  @OA\Property(property="suggested_start_date", type="string"),
     *                  @OA\Property(property="days",type="integer")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_plan_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_plan_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_plan_index")),
     *         @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_plan_index"))
     *     )
     * )
     *
     * @return \Illuminate\Http\Response|array
     */
    public function store($user_id)
    {

        // Validate Project / User Connection
        $user = User::where('id', $user_id)->select('id')->first();

        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404'));
        }

        $user_is_member = $this->compareProjects($user_id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $name = checkParam('name', true);
        $days = checkParam('days', true);
        $suggested_start_date = checkParam('suggested_start_date');

        $plan = Plan::create([
            'user_id'           => $user_id,
            'name'              => $name,
            'featured'          => false
        ]);

        for ($i = 0; $i < intval($days); $i++) {
            $plan_day = new PlanDay(array('plan_id' => $plan->id));
            $plan_day->save();
        }

        UserPlan::create([
            'user_id'               => $user_id,
            'plan_id'               => $plan->id,
            'suggested_start_date'  => $suggested_start_date,
        ]);

        return $this->reply($plan);
    }

    /**
     * Remove the specified plan.
     *
     * @OA\Delete(
     *     path="/plans/{user_id}/{plan_id}",
     *     tags={"Plans"},
     *     summary="Delete a plan",
     *     description="",
     *     operationId="v4_plans.destroy",
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="plan_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Plan/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
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
     * @param  int $user_id
     * @param  int $plan_id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function destroy($user_id, $plan_id)
    {
        // Validate Project / User Connection
        $user = User::where('id', $user_id)->select('id')->first();

        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404'));
        }

        $user_is_member = $this->compareProjects($user_id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $plan = Plan::where('user_id', $user_id)->where('id', $plan_id)->first();

        if (!$plan) {
            return $this->setStatusCode(404)->replyWithError('Plan Not Found');
        }

        $plan->days()->delete();
        $plan->delete();

        return $this->reply('Plan Deleted');
    }

    private function validatePlan()
    {
        $validator = Validator::make(request()->all(), [
            'user_id'           => 'required|exists:dbp_users.users,id',
            'name'              => 'required|string'
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        return true;
    }
}
