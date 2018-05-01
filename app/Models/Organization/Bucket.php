<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\Bucket
 *
 * @property string $id
 * @property int $organization_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Bucket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Bucket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Bucket whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Bucket whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $hidden
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Bucket whereHidden($value)
 */
class Bucket extends Model
{
	public $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function organization()
    {
    	return $this->belongsTo(Organization::class);
    }

}
