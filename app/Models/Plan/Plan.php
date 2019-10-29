<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\User\User;

/**
 * App\Models\Plan
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $user_id
 * @property bool $featured
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *
 * @OA\Schema (
 *     type="object",
 *     description="The User created Plan",
 *     title="Plan"
 * )
 *
 */
class Plan extends Model
{
    use SoftDeletes;

    protected $connection = 'dbp_users';
    public $table         = 'plans';
    protected $fillable   = ['user_id', 'name', 'suggested_start_date'];
    protected $hidden     = ['user_id', 'deleted_at', 'plan_id'];
    protected $dates      = ['deleted_at'];

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The plan id",
     *   minimum=0
     * )
     *
     */
    protected $id;
    /**
     *
     * @OA\Property(
     *   title="name",
     *   type="string",
     *   description="The name of the plan"
     * )
     *
     */
    protected $name;
    /**
     *
     * @OA\Property(
     *   title="user_id",
     *   type="string",
     *   description="The user that created the plan"
     * )
     *
     */
    protected $user_id;
    /**
     *
     * @OA\Property(
     *   title="featured",
     *   type="boolean",
     *   description="If the plan is featured"
     * )
     *
     */
    protected $featured;
    /**
     *
     * @OA\Property(
     *   title="thumbnail",
     *   type="string",
     *   description="The image url",
     *   maxLength=191
     * )
     *
     */
    protected $thumbnail;
    /**
     *
     * @OA\Property(
     *   title="suggested_start_date",
     *   type="string",
     *   format="date",
     *   description="The suggested start date of the plan"
     * )
     *
     */
    protected $suggested_start_date;
    /** @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp the plan was last updated at",
     *   nullable=true
     * )
     *
     * @method static Note whereUpdatedAt($value)
     * @public Carbon|null $updated_at
     */
    protected $updated_at;
    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The timestamp the plan was created at"
     * )
     *
     * @method static Note whereCreatedAt($value)
     * @public Carbon $created_at
     */
    protected $created_at;
    protected $deleted_at;

    public function getFeaturedAttribute($featured)
    {
        return (bool) $featured;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }

    public function days()
    {
        return $this->hasMany(PlanDay::class)->orderBy('order_column');
    }
}
