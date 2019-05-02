<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class AccessGroupType extends Model
{
    protected $connection = 'dbp';
    public $table = 'access_group_types';

    public function access()
    {
        return $this->belongsTo(AccessGroup::class, 'access_group_id');
    }
}
