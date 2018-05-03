<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BibleFilesetCopyright
 *
 * @OAS\Schema (
 *     type="object",
 *     description="BibleFilesetCopyright",
 *     title="Bible Fileset Copyright",
 *     @OAS\Xml(name="BibleFilesetCopyright")
 * )
 *
 * @package App\Models\Bible
 */
class BibleFilesetCopyright extends Model
{

	public $table = 'bible_fileset_copyrights';
	protected $primaryKey = 'hash_id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = false;

	/**
	 *
	 * @OAS\Property(
	 *   title="hash id",
	 *   type="string",
	 *   description="The hash value for the created fileset",
	 *   default="available",
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
	 * @OAS\Property(
	 *   title="copyright_date",
	 *   type="string",
	 *   description="The copyright date created copyright",
	 *   default="available",
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
	 * @OAS\Property(
	 *   title="copyright",
	 *   type="string",
	 *   description="The copyright",
	 *   default="available"
	 * )
	 *
	 * @method static BibleFilesetCopyright whereCopyright($value)
	 * @property string $copyright
	 *
	 */
	protected $copyright;

	/**
	 *
	 * @OAS\Property(
	 *   title="copyright_description",
	 *   type="string",
	 *   description="The copyright description",
	 *   default="available"
	 * )
	 *
	 * @method static BibleFilesetCopyright whereCopyrightDescription($value)
	 * @property string $copyright_description
	 *
	 */
	protected $copyright_description;

	/**
	 *
	 * @OAS\Property(
	 *   title="open_access",
	 *   type="string",
	 *   description="The open_access description",
	 *   default="available"
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
		return $this->HasManyThrough(Organization::class, BibleFilesetCopyrightOrganization::class, 'hash_id', 'id', 'hash_id', 'organization_id');
	}

	public function fileset()
	{
		return $this->belongsTo(Organization::class());
	}

	public function roles()
	{
		return $this->HasManyThrough(BibleFilesetCopyrightRole::class, BibleFilesetCopyrightOrganization::class, 'hash_id', 'hash_id', 'id', 'organizations_id');
	}

}
