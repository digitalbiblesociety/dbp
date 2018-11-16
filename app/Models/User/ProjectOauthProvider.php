<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectOauthProvider
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Project's oAuth provider model",
 *     title="ProjectOauthProvider",
 *     @OA\Xml(name="ProjectOauthProvider")
 * )
 *
 * @package App\Models\User
 */
class ProjectOauthProvider extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'project_oauth_providers';
    protected $fillable = ['id','project_id','name','client_secret','client_id','callback_url','callback_url_alt','description'];
    public $incrementing = false;
    public $keyType = 'string';

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The human readable id for an oauth provider",
     *   maxLength=8
     * )
     *
     * @method static Project whereId($value)
     * @property string $id
     */
    protected $id;


    /**
     *
     * @OA\Property(ref="#/components/schemas/Project/properties/id")
     *
     * @method static Project whereProjectId($value)
     * @property string $project_id
     */
    protected $project_id;

    /**
     *
     * @OA\Property(
     *   title="name",
     *   type="string",
     *   description="The name for an oauth provider",
     *   maxLength=191
     * )
     *
     * @method static Project whereName($value)
     * @property string $name
     */
    protected $name;

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The id for an oauth provider",
     *   maxLength=191
     * )
     *
     * @method static Project whereClientId($value)
     * @property string $client_id
     */
    protected $client_id;

    /**
     *
     * @OA\Property(
     *   title="secret",
     *   type="string",
     *   description="The secret for an oauth provider",
     *   maxLength=191
     * )
     *
     * @method static Project whereClientSecret($value)
     * @property string $client_secret
     */
    protected $client_secret;

    /**
     *
     * @OA\Property(
     *   title="callback_url",
     *   type="string",
     *   description="The callback_url for an oauth provider",
     *   maxLength=191
     * )
     *
     * @method static Project whereCallbackUrl($value)
     * @property string $callback_url
     */
    protected $callback_url;

    /**
     *
     * @OA\Property(
     *   title="callback_url_alt",
     *   type="string",
     *   description="An alternative callback_url for an oauth provider",
     *   maxLength=191
     * )
     *
     * @method static Project whereCallbackUrlAlt($value)
     * @property string $callback_url_alt
     */
    protected $callback_url_alt;

    /**
     *
     * @OA\Property(
     *   title="description",
     *   type="string",
     *   description="The description for an oauth provider",
     *   maxLength=191
     * )
     *
     * @method static Project whereDescription($value)
     * @property string $description
     */
    protected $description;
}
