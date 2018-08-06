<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetTag
 *
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 * @property string $set_id
 * @property string $hash_id
 *
 * @OA\Schema (
 *     type="object",
 *     required={"filename"},
 *     description="The Bible fileset tag model communicates general metadata about the filesets",
 *     title="BibleFilesetSize",
 *     @OA\Xml(name="BibleFilesetSize")
 * )
 *
 */
class BibleFilesetTag extends Model
{
	protected $connection = 'dbp';
	public $table = 'bible_fileset_tags';
	public $primaryKey = 'set_id';
	public $incrementing = false;
	protected $keyType = "string";
	protected $hidden = ["created_at","updated_at",'bible_fileset_id','admin_only','notes'];
	protected $fillable = ['name','description','admin_only','notes','iso'];

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/BibleFileset/properties/hash_id")
	 * @method static BibleFilesetTag whereHashId($value)
	 * @property string $hash_id
	 *
	 */
	protected $hash_id;

	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name of the tag, serves as the key/category",
	 *   maxLength=191
	 * )
	 *
	 * @method static BibleFilesetTag whereName($value)
	 * @property string $name
	 *
	 */
	protected $name;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The content of the tag, serves as the value of the key value pair of name/description"
	 * )
	 *
	 * @method static BibleFilesetTag whereDescription($value)
	 * @property string $description
	 *
	 */
	protected $description;

	/**
	 *
	 * @OA\Property(
	 *   title="admin_only",
	 *   type="boolean",
	 *   description="If the tag is only to be visible to admin / archivist users"
	 * )
	 *
	 * @method static BibleFilesetTag whereAdminOnly($value)
	 * @property string $admin_only
	 *
	 */
	protected $admin_only;

	/**
	 *
	 * @OA\Property(
	 *   title="notes",
	 *   type="string",
	 *   description="Any notes about the tag"
	 * )
	 *
	 * @method static BibleFilesetTag whereNotes($value)
	 * @property string $notes
	 *
	 */
	protected $notes;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Language/properties/iso")
	 * @method static BibleFilesetTag whereIso($value)
	 * @property string $iso
	 *
	 */
	protected $iso;

	public function fileset()
	{
		return $this->belongsTo(BibleFileset::class);
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

}
