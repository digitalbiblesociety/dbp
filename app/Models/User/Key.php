<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Key
 *
 * @property string $user_id
 * @property string $key
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Key whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Key whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Key whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Key whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Key whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Key whereUserId($value)
 * @mixin \Eloquent
 */
class Key extends Model
{
    public $table = 'user_keys';
    protected $primaryKey = 'key';
    public $incrementing = 'false';
    protected $keyType = 'string';

    public function user() {
    	return $this->belongsTo(User::class);
	}

}
