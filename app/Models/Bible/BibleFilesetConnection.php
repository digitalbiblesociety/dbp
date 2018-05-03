<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetConnection
 *
 * @mixin \Eloquent
 *
 * @property-read Bible $bible
 * @property-read BibleFileset $fileset
 * @property-read BibleFilesetSize $size
 * @property-read BibleFilesetType $type
 *
 * @OAS\Schema (
 *     type="object",
 *     description="BibleFilesetConnection",
 *     title="BibleFileset Connection",
 *     @OAS\Xml(name="BibleFilesetConnection")
 * )
 *
 */
class BibleFilesetConnection extends Model
{
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'hash_id';

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/BibleFileset/properties/hash_id")
	 * @method static BibleFilesetConnection whereHashId($value)
	 * @property string $hash_id
	 */
	protected $hash_id;
	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Bible/properties/id")
	 * @method static BibleFilesetConnection whereBibleId($value)
	 * @property string $bible_id
	 */
	protected $bible_id;
	/**
	 *
	 * @method static BibleFilesetConnection whereCreatedAt($value)
	 * @property Carbon $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @method static BibleFilesetConnection whereUpdatedAt($value)
	 * @property Carbon $updated_at
	 */
	protected $updated_at;

    public function fileset()
    {
    	return $this->belongsTo(BibleFileset::class);
    }

	public function bible()
	{
		return $this->belongsTo(Bible::class);
	}

	public function size()
	{
		return $this->belongsTo(BibleFilesetSize::class);
	}

	public function type()
	{
		return $this->belongsTo(BibleFilesetType::class);
	}

}
