<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Study\Commentary
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="Commentary",
 *     title="Commentary",
 *     @OA\Xml(name="Commentary")
 * )
 *
 */
class Commentary extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $connection = 'dbp';
    protected $fillable = ['id', 'type', 'author', 'date', 'features', 'publisher'];
    protected $hidden = ['created_at','updated_at'];

    /**
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The unique ID for the commentary, for example the English Treasure of Scripture Knowledge id is ENGTSK",
     *   minLength=6,
     *   maxLength=12,
     *   example="ENGTSK"
     * )
     */
    protected $id;

    /**
     * @OA\Property(
     *   title="type",
     *   type="string",
     *   description="The genre of commentary being described",
     *   minLength=9,
     *   maxLength=11,
     *   enum={"critical", "devotional", "pastoral", "exegetical"}
     * )
     */
    protected $type;

    /**
     * @OA\Property(
     *   title="type",
     *   type="string",
     *   description="The author of the commentary",
     *   maxLength=191,
     * )
     */
    protected $author;

    /**
     * @OA\Property(
     *   title="date",
     *   type="integer",
     *   description="The year the commentary was published",
     *   example=1991,
     * )
     */
    protected $date;

    /**
     * @OA\Property(
     *   title="date",
     *   type="integer",
     *   description="The year the commentary was published",
     *   example=1991,
     * )
     */
    protected $features;

    /**
     * @OA\Property(
     *   title="publisher",
     *   type="string",
     *   description="The original publisher of the commentary",
     *   example=1991,
     * )
     */
    protected $publisher;


    public function sections()
    {
        return $this->hasMany(CommentarySection::class);
    }

    public function translations()
    {
        return $this->hasMany(CommentaryTranslation::class);
    }

    public function vernacular()
    {
        return $this->hasOne(CommentaryTranslation::class)->where('vernacular', 1);
    }
}
