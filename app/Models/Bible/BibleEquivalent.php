<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\Organization;

/**
 * App\Models\Bible\BibleEquivalent
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Bible Equivalent Model stores the connections between the bible IDs and external organizations",
 *     title="BibleEquivalent",
 *     @OA\Xml(name="BibleEquivalent")
 * )
 *
 */
class BibleEquivalent extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_equivalents';
    protected $primaryKey = 'equivalent_id';
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = ['bible_id','equivalent_id','organization_id','type','suffix','needs_review','constructed_url','site'];
    public $incrementing = false;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Bible/properties/id")
     * @method static BibleEquivalent whereBibleId($value)
     * @property string $bible_id
     *
    */
    protected $bible_id;

    /**
     *
     * @OA\Property(
     *   title="equivalent_id",
     *   type="string",
     *   description="The equivalent_id",
     *   maxLength=191,
     *   example="FreGeneve1669"
     * )
     * @method static BibleEquivalent whereEquivalentId($value)
     * @property string $equivalent_id
     *
     */
    protected $equivalent_id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Organization/properties/id")
     * @method static BibleEquivalent whereOrganizationId($value)
     * @property int $organization_id
     *
     */
    protected $organization_id;

    /**
     *
     * @OA\Property(
     *   title="type",
     *   type="string",
     *   description="The type of connection that the equivalent id refers to",
     *   maxLength=191,
     *   example="desktop-app"
     * )
     * @method static BibleEquivalent whereType($value)
     * @property string $type
     *
     */
    protected $type;

    /**
     *
     * @OA\Property(
     *   title="site",
     *   type="string",
     *   description="The name of the site/organization/app where the equivalent id is based",
     *   maxLength=191,
     *   example="eSword"
     * )
     * @method static BibleEquivalent whereSite($value)
     * @property string $site
     *
     */
    protected $site;

    /**
     *
     * @OA\Property(
     *   title="site",
     *   type="string",
     *   description="Additional metadata affecting the type of equivalent connection",
     *   maxLength=191,
     *   example="Authorized Version with Strong's"
     * )
     * @method static BibleEquivalent whereSuffix($value)
     * @property string $suffix
     *
     */
    protected $suffix;

    /**
     *
     * @OA\Property(
     *   title="Constructed Url",
     *   type="string",
     *   description="The full path to the resource which the equivalent describes",
     *   maxLength=191,
     *   example="https://example.com/bibles/ENGKJV"
     * )
     * @method static BibleEquivalent whereSuffix($value)
     * @property string $suffix
     *
     */
    protected $constructed_url;

    public function bible()
    {
        return $this->belongsTo(Bible::class, 'bible_id', 'id');
    }

    public function organization()
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }
}
