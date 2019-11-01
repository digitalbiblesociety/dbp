<?php

namespace App\Http\Controllers\User;

use App\Traits\AccessControlAPI;
use App\Http\Controllers\APIController;
use App\Models\User\PushToken;
use App\Traits\CheckProjectMembership;
use Illuminate\Http\Request;

class PushTokensController extends APIController
{
    use AccessControlAPI;
    use CheckProjectMembership;

    /**
     *
     * @OA\Get(
     *     path="/push_notifications",
     *     tags={"Push Notifications"},
     *     summary="List a user's push notification tokens",
     *     operationId="v4_push_tokens.index",
     *     security={{"api_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_push_token_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_push_token_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_push_token_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_push_token_index"))
     *     )
     * )
     *
     *
     * @return mixed
     *
     * @OA\Schema (
     *    type="array",
     *    schema="v4_push_token_index",
     *    description="The v4 push tokens index response.",
     *    title="v4_push_token_index",
     *    @OA\Items(
     *         @OA\Property(property="push_token", ref="#/components/schemas/PushToken/properties/push_token"),
     *         @OA\Property(property="platform", ref="#/components/schemas/PushToken/properties/platform")
     *    ),
     * )
     */


    public function index(Request $request)
    {
        $user = $request->user();

        // Validate Project / User Connection
        if (!empty($user) && !$this->compareProjects($user->id, $this->key)) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $push_tokens = PushToken::where('user_id', $user->id)->get();

        return $this->reply($push_tokens);
    }

    /**
     *
     * @OA\Post(
     *     path="/push_notifications",
     *     tags={"Push Notifications"},
     *     summary="Crete a push token",
     *     operationId="v4_push_tokens.store",
     *     security={{"api_token":{}}},
     *     @OA\RequestBody(required=true, description="Fields for User Push Token Creation",
     *           @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="token", ref="#/components/schemas/PushToken/properties/push_token"),
     *                  @OA\Property(property="platform", ref="#/components/schemas/PushToken/properties/platform")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_push_token_detail")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_push_token_detail")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_push_token_detail")),
     *         @OA\MediaType(mediaType="text/csv",         @OA\Schema(ref="#/components/schemas/v4_push_token_detail"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v4_push_token_detail",
     *     description="v4_push_token_detail",
     *     title="v4_push_token_detail",
     *     @OA\Property(property="push_token", ref="#/components/schemas/PushToken/properties/push_token"),
     *     @OA\Property(property="platform", ref="#/components/schemas/PushToken/properties/platform")
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

        $token = checkParam('token', true);
        $platform = checkParam('platform', true);

        if (PushToken::where('push_token', $token)->exists()) {
            return $this->setStatusCode(409)->replyWithError('Push token already exists');
        }

        $push_token = PushToken::create([
            'user_id'               => $user->id,
            'push_token'            => $token,
            'platform'              => strtolower($platform)
        ]);

        return $this->reply($push_token);
    }

    /**
     * @OA\Delete(
     *     path="/push_notifications/{token}",
     *     tags={"Push Notifications"},
     *     summary="Delete a push token",
     *     operationId="v4_push_tokens.destroy",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="token", in="path", required=true, @OA\Schema(ref="#/components/schemas/PushToken/properties/push_token")),
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
    public function destroy(Request $request, $token)
    {
        // Validate Project / User Connection
        $user = $request->user();
        $user_is_member = $this->compareProjects($user->id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $push_token = PushToken::where('user_id', $user->id)->where('push_token', $token)->first();

        if (!$push_token) {
            return $this->setStatusCode(404)->replyWithError('Push Token Not Found');
        }

        $push_token->delete();

        return $this->reply('Push token Deleted');
    }
}
