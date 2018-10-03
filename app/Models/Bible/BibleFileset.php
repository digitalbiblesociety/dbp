<?php

namespace App\Models\Bible;

use App\Models\Organization\Bucket;
use App\Models\Organization\Organization;
use App\Models\User\AccessGroup;
use App\Models\User\AccessGroupFileset;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFileset
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="BibleFileset",
 *     title="Bible Fileset",
 *     @OA\Xml(name="BibleFileset")
 * )
 *
 */
class BibleFileset extends Model
{
	protected $connection = 'dbp';
	public $incrementing = false;
	protected $keyType = 'string';
	protected $hidden = ['created_at','updated_at','response_time','hidden','bible_id','hash_id'];
	protected $fillable = ['name','set_type','organization_id','variation_id','bible_id','set_copyright'];


	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The fileset id",
	 *   minLength=6,
	 *   maxLength=16
	 * )
	 *
	 * @method static BibleFileset whereId($value)
	 * @property string $id
	 */
	protected $id;

	/**
	 *
	 * @OA\Property(
	 *   title="hash_id",
	 *   type="string",
	 *   description="The hash_id generated from the `bucket_id`, `set_type_code`, and `id`",
	 *   minLength=12,
	 *   maxLength=12
	 * )
	 *
	 * @method static BibleFileset whereHashId($value)
	 * @property hash_id $hash_id
	 */
	protected $hash_id;

	/**
	 *
	 * @OA\Property(
	 *   title="bucket_id",
	 *   type="string",
	 *   description="The bucket id of the AWS Bucket",
	 *   maxLength=64
	 * )
	 *
	 * @method static BibleFileset whereBucketId($value)
	 * @property string $bucket_id
	 */
	protected $bucket_id;

	/**
	 *
	 * @OA\Property(
	 *   title="set_type_code",
	 *   type="string",
	 *   description="The set_type_code indicating the type of the fileset",
	 *   maxLength=3
	 * )
	 *
	 * @method static BibleFileset whereSetTypeCode($value)
	 * @property string $set_type_code
	 */
	protected $set_type_code;

	/**
	 *
	 * @OA\Property(
	 *   title="set_size_code",
	 *   type="string",
	 *   description="The set_size_code indicating the size of the fileset",
	 *   maxLength=3
	 * )
	 *
	 * @method static BibleFileset whereSetSizeCode($value)
	 * @property string $set_size_code
	 */
	protected $set_size_code;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp at which the fileset was originally created"
	 * )
	 *
	 * @method static Bible whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp at which the fileset was last updated"
	 * )
	 *
	 * @method static Bible whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;

	public function copyright()
	{
		return $this->hasOne(BibleFilesetCopyright::class,'hash_id','hash_id');
	}

	public function copyrightOrganization()
    {
        return $this->hasMany(BibleFilesetCopyrightOrganization::class,'hash_id','hash_id');
    }

	public function permissions()
	{
		return $this->hasMany(AccessGroupFileset::class,'hash_id','hash_id');
	}

	public function bible()
	{
		return $this->hasManyThrough(Bible::class,BibleFilesetConnection::class, 'hash_id','id','hash_id','bible_id');
	}

	public function translations()
	{
		return $this->hasManyThrough(BibleTranslation::class,BibleFilesetConnection::class, 'hash_id','bible_id','hash_id','bible_id');
	}

	public function connections()
	{
		return $this->hasOne(BibleFilesetConnection::class,'hash_id', 'hash_id');
	}

	public function organization()
	{
		return $this->hasManyThrough(Organization::class,Bucket::class,'id','id','bucket_id','organization_id');
	}

	public function files()
	{
		return $this->hasMany(BibleFile::class,'hash_id', 'hash_id');
	}

	public function meta()
	{
		return $this->hasMany(BibleFilesetTag::class,'hash_id','hash_id');
	}
}
