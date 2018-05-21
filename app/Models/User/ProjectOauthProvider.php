<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectOauthProvider
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Project's oAuth provider model",
 *     title="ProjectOauthProvider",
 *     @OAS\Xml(name="ProjectOauthProvider")
 * )
 *
 * @package App\Models\User
 */
class ProjectOauthProvider extends Model
{
    protected $table = "project_oauth_providers";
	protected $fillable = ['id','project_id','name','client_secret','client_id','callback_url','description'];
	public $incrementing = false;
	public $keyType = 'string';

	public function setIdAttribute($id)
	{
		$length     = 8;
		$string     = '';
		$vowels     = array("a","e","i","o","u");
		$consonants = array(
			'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
			'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
		);
		// Seed it
		srand((double) microtime() * 1000000);
		$max = $length/2;
		for ($i = 1; $i <= $max; $i++)
		{
			$string .= $consonants[rand(0,19)];
			$string .= $vowels[rand(0,4)];
		}
		return $this->attributes['id'] = $string;
	}


	/**
	 * Encrypt Client Secrets as they're stored in the database
	 *
	 */
	public function setClientSecretAttribute($secret)
	{
		return $this->attributes['client_secret'] = encrypt($secret);
	}

	/**
	 * Decrypt Client Secrets as they're returned to API users
	 *
	 */
	public function getClientSecretAttribute($secret)
	{
		return $this->attributes['client_secret'] = decrypt($secret);
	}

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(ref="#/components/schemas/Project/properties/id")
	 *
	 * @method static Project whereProjectId($value)
	 * @property string $id
	 */
	protected $project_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name for an oauth provider",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereId($value)
	 * @property string $id
	 */
	protected $name;

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The id for an oauth provider",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereId($value)
	 * @property string $id
	 */
	protected $client_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="secret",
	 *   type="string",
	 *   description="The secret for an oauth provider",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereId($value)
	 * @property string $id
	 */
	protected $client_secret;

	/**
	 *
	 * @OAS\Property(
	 *   title="callback_url",
	 *   type="string",
	 *   description="The callback_url for an oauth provider",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereId($value)
	 * @property string $id
	 */
	protected $callback_url;

	/**
	 *
	 * @OAS\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The description for an oauth provider",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereId($value)
	 * @property string $id
	 */
	protected $description;

}
