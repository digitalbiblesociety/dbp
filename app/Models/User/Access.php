<?php

namespace App\Models\User;

use App\Models\Bible\BibleFileset;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;

/**
 * App\Models\User\Access
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\Bible\Bible|null $bible
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $user
 * @property-read \App\Models\Bible\BibleFileset $fileset
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Access",
 *     title="Access",
 *     @OAS\Xml(name="Access")
 * )
 *
 */
class Access extends Model
{
	protected $table = 'user_access';
	protected $primaryKey = 'key_id';
	public $incrementing = false;

	/**
	 *
	 * @OAS\Property(
	 *   title="key_id",
	 *   type="string",
	 *   description="The key of the user that has the permission being described",
	 *   default="available"
	 * )
	 *
	 * @property int $key_id
	 * @method static Access whereKeyId($value)
	 */
	protected $key_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="hash_id",
	 *   type="string",
	 *   description="The hash_id for the fileset for which the user's access is being altered",
	 *   default="available"
	 * )
	 *
	 * @property int $key_id
	 * @method static Access whereHashId($value)
	 */
	protected $hash_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="access_notes",
	 *   type="string",
	 *   description="The Notes for the connection between fileset and user",
	 *   default="available"
	 * )
	 *
	 * @property int $key_id
	 * @method static Access whereHashId($value)
	 */
	protected $access_notes;

	/**
	 *
	 * @OAS\Property(
	 *   title="access_type",
	 *   type="string",
	 *   description="The type of access that the user has for the fileset",
	 *   default="available"
	 * )
	 *
	 * @property string $access_type
	 * @method static Access whereAccessType($value)
	 */
	protected $access_type;

	/**
	 *
	 * @OAS\Property(
	 *   title="access_granted",
	 *   type="boolean",
	 *   description="If the access being described is granted or denied for the user",
	 *   default="available"
	 * )
	 *
	 * @property string $access_granted
	 * @method static Access whereAccessType($value)
	 */
	protected $access_granted;

	protected $fillable = ['key_id','access_type','access_notes','hash_id'];

	public function bible()
	{
		return $this->BelongsTo(Bible::class,'bible_id','id');
	}

	public function fileset()
	{
		return $this->BelongsTo(BibleFileset::class,'hash_id','hash_id');
	}

	public function user()
	{
		return $this->hasManyThrough(User::class,Key::class,'key','id','key_id','user_id');
	}

}
