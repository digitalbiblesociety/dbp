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

    public function commentary()
    {
        return $this->belongsTo(Commentary::class);
    }

}
