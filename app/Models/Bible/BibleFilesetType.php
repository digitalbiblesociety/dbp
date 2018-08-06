<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetType
 * @mixin \Eloquent
 *
 * @property-read \App\Models\Bible\BibleFileset $fileset
 *
 * @OA\Schema (
 *     type="object",
 *     required={"filename"},
 *     description="The Bible Fileset Type model communicates general metadata about the bible_filesets.set_size_code",
 *     title="BibleFilesetType",
 *     @OA\Xml(name="BibleFilesetType")
 * )
 *
 */
class BibleFilesetType extends Model
{
	protected $connection = 'dbp';
    public $table = "bible_fileset_types";

    protected $hidden = ['updated_at','id'];

	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static BibleFilesetType whereId($value)
	 * @property int $id
	 */
	protected $id;
	/**
	 *
	 * @OA\Property(
	 *   title="set_type_code",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static BibleFilesetType whereSetTypeCode($value)
	 * @property string $set_type_code
	 */
	protected $set_type_code;
	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static BibleFilesetType whereName($value)
	 * @property string $name
	 */
	protected $name;
	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static BibleFilesetType whereCreatedAt($value)
	 * @property Carbon $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static BibleFilesetType whereUpdatedAt($value)
	 * @property Carbon $updated_at
	 */
	protected $updated_at;

    public function fileset()
    {
    	return $this->belongsTo(BibleFileset::class);
    }

}
