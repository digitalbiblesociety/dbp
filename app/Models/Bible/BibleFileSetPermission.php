<?php

namespace App\Models\Bible;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFileSetPermission
 *
 * @property int $id
 * @property string $bible_fileset_id
 * @property string $user_id
 * @property string $access_level
 * @property string|null $access_notes
 * @property string $first_response_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @property-read \App\Models\User\User $user
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereAccessNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereBibleFilesetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereFirstResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileSetPermission whereUserId($value)
 */
class BibleFileSetPermission extends Model
{
	protected $table = "bible_file_permissions";
	protected $fillable = ['user_id', 'fileset_id', 'access_level', 'access_notes', 'bible_fileset_id'];

	public function fileset()
	{
		return $this->BelongsTo(BibleFileset::class,'bible_fileset_id');
	}

	public function user()
	{
		return $this->BelongsTo(User::class);
	}
}
