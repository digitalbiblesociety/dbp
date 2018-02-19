<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetTag
 *
 * @property string $bible_fileset_id
 * @property string $name
 * @property string $description
 * @property int $admin_only
 * @property string $notes
 * @property string $iso
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereAdminOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereBibleFilesetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $set_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereSetId($value)
 * @property string $hash_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetTag whereHashId($value)
 */
class BibleFilesetTag extends Model
{
	public $table = 'bible_fileset_tags';
	public $primaryKey = 'set_id';
	public $incrementing = false;
	protected $keyType = "string";
	protected $hidden = ["created_at","updated_at",'bible_fileset_id','admin_only','notes'];
	protected $fillable = ['name','description','admin_only','notes','iso'];

	public function fileset()
	{
		return $this->belongsTo(BibleFileset::class);
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

}
