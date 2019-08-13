<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class PlanDay extends Model implements Sortable
{
    use SortableTrait;

    protected $connection = 'dbp_users';
    public $table         = 'plan_days';
    protected $fillable   = ['plan_id', 'playlist_id'];
    protected $hidden     = ['plan_id', 'created_at', 'updated_at', 'order_column'];
}
