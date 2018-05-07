<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Key
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Key's model",
 *     title="Key",
 *     @OAS\Xml(name="Key")
 * )
 *
 */
class Key extends Model
{
    public $table = 'user_keys';
    protected $primaryKey = 'key';
    public $incrementing = 'false';
    protected $keyType = 'string';

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/User/properties/id")
	 * @method static Key whereUserId($value)
	 * @property string $user_id
	 */
	protected $user_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="key",
	 *   type="string",
	 *   description="The unique generated api key for Key model",
	 *   maxLength=64
	 * )
	 *
	 * @method static Key whereKey($value)
	 * @property string $key
	 */
	protected $key;
	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The user provided distinctive name to differentiate different keys provided to the same user.",
	 *   maxLength=191
	 * )
	 *
	 * @method static Key whereName($value)
	 * @property string $name
	 */
	protected $name;
	/**
	 *
	 * @OAS\Property(
	 *   title="description",
	 *   type="string",
	 *   description="Any additional identifying information about the key provided and it's use can be stored here"
	 * )
	 *
	 * @method static Key whereDescription($value)
	 * @property string $description
	 */
	protected $description;
	/**
	 *
	 * @OAS\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp at which the key was created at"
	 * )
	 *
	 * @method static Key whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @OAS\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp at which the key was last updated at"
	 * )
	 *
	 * @method static Key whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;

	/**
	 * @property-read \App\Models\User\User $user
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function user() {
    	return $this->belongsTo(User::class);
	}

}
