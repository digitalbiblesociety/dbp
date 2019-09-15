<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Study\CommentaryTranslation
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="CommentaryTranslation",
 *     title="CommentaryTranslation",
 *     @OA\Xml(name="CommentaryTranslation")
 * )
 *
 */
class CommentaryTranslation extends Model
{
    protected $connection = 'dbp';

    protected $hidden = ['created_at','updated_at','id','commentary_id'];

    /**
     * @var integer $language_id
     * @OA\Property(ref="#/components/schemas/CommentaryTranslation/properties/language_id")
     */
    protected $language_id;

    /**
     * @var string $commentary_id
     * @OA\Property(ref="#/components/schemas/Commentary/properties/id")
     */
    protected $commentary_id;

    /**
     * @var string $vernacular
     * @OA\Property()
     */
    protected $vernacular;

    /**
     * @var string $name
     * @OA\Property()
     */
    protected $name;

    /**
     * @var string $description
     * @OA\Property()
     */
    protected $description;

    public function commentary()
    {
        return $this->belongsTo(Commentary::class);
    }
}
