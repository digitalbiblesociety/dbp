<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\Organization;

/**
 * Class BibleFilesetCopyright
 *
 * @OA\Schema (
 *     type="object",
 *     description="BibleFilesetCopyright",
 *     title="Bible Fileset Copyright",
 *     @OA\Xml(name="BibleFilesetCopyright")
 * )
 *
 * @package App\Models\Bible
 */
class BibleFilesetCopyright extends Model
{
	protected $connection = 'dbp';
	public $table = 'bible_fileset_copyrights';
	protected $primaryKey = 'hash_id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = false;

	/**
	 *
	 * @OA\Property(
	 *   title="hash id",
	 *   type="string",
	 *   description="The hash value for the created fileset",
	 *   minLength=12,
	 *   maxLength=12,
	 *   example="ENGESV"
	 * )
	 *
	 * @method static BibleFilesetCopyright whereHashId($value)
	 * @property string $hash_id
	 *
	 */
	protected $hash_id;

	/**
	 *
	 * @OA\Property(
	 *   title="copyright_date",
	 *   type="string",
	 *   description="The copyright date created copyright",

	 *   example="ENGESV"
	 * )
	 *
	 * @method static BibleFilesetCopyright whereCopyrightDate($value)
	 * @property string $copyright_date
	 *
	 */
	protected $copyright_date;

	/**
	 *
	 * @OA\Property(
	 *   title="copyright",
	 *   type="string",
	 *   description="The copyright"
	 * )
	 *
	 * @method static BibleFilesetCopyright whereCopyright($value)
	 * @property string $copyright
	 *
	 */
	protected $copyright;

	/**
	 *
	 * @OA\Property(
	 *   title="copyright_description",
	 *   type="string",
	 *   description="The copyright description",
	 * )
	 *
	 * @method static BibleFilesetCopyright whereCopyrightDescription($value)
	 * @property string $copyright_description
	 *
	 */
	protected $copyright_description;

	/**
	 *
	 * @OA\Property(
	 *   title="open_access",
	 *   type="string",
	 *   description="The open_access description",
	 * )
	 *
	 * @method static BibleFilesetCopyright whereOpenAccess($value)
	 * @property string $open_access
	 *
	 */
	protected $open_access;

	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function organizations()
	{
		return $this->hasManyThrough(Organization::class, BibleFilesetCopyrightOrganization::class, 'hash_id', 'id', 'hash_id', 'organization_id');
	}

	public function role()
	{
		return $this->hasOne(BibleFilesetCopyrightOrganization::class,'hash_id','hash_id');
	}

	public function fileset()
	{
		return $this->belongsTo(Organization::class());
	}

	public function roles()
	{
		return $this->hasManyThrough(BibleFilesetCopyrightRole::class, BibleFilesetCopyrightOrganization::class, 'hash_id', 'hash_id', 'id', 'organizations_id');
	}

}
