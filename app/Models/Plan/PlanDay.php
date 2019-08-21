<?php

namespace App\Models\Plan;

use App\Models\Playlist\Playlist;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * App\Models\Plan
 * @mixin \Eloquent
 * 
 * @property int $plan_id
 * @property int $playlist_id
 *
 * @OA\Schema (
 *     type="object",
 *     description="The day of a Plan",
 *     title="Plan day",
 *     @OA\Xml(name="PlanDay")
 * )
 *
 */
class PlanDay extends Model implements Sortable
{
    use SortableTrait;

    protected $connection = 'dbp_users';
    public $table         = 'plan_days';
    protected $fillable   = ['plan_id', 'playlist_id'];
    protected $hidden     = ['plan_id', 'created_at', 'updated_at', 'order_column'];

    /**
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The plan day id"
     * )
     */
    protected $id;

    /**
     * @OA\Property(ref="#/components/schemas/Playlist/properties/id")
     */
    protected $playlist_id;

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
