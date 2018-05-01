<?php

namespace App\Models\User;

use App\Models\Bible\BibleFileset;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;

/**
 * App\Models\User\Access
 *
 * @property int $id
 * @property string $key_id
 * @property string $user_id
 * @property string|null $bible_id
 * @property string $fileset_id
 * @property int|null $organization_id
 * @property int $whitelist
 * @property int $access_api
 * @property int $access_apps
 * @property int $access_store
 * @property int $access_stream
 * @property int $access_iTunes
 * @property int $access_fairUse
 * @property int $access_website
 * @property int $access_download
 * @property int $access_peer2peer
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible|null $bible
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessApps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessFairUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessITunes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessPeer2peer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessStream($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereFilesetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereKeyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereWhitelist($value)
 * @mixin \Eloquent
 * @property string|null $access_notes
 * @property string|null $access_type
 * @property int $access_given
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessGiven($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessType($value)
 * @property int $access_granted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereAccessGranted($value)
 * @property string $hash_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Access whereHashId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $user
 * @property-read \App\Models\Bible\BibleFileset $fileset
 */
class Access extends Model
{
	protected $table = 'user_access';
	protected $primaryKey = 'key_id';
	public $incrementing = false;

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
