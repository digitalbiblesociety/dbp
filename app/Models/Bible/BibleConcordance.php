<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema (
 *     type="object",
 *     description="The BibleConcordance",
 *     title="BibleConcordance",
 *     @OA\Xml(name="BibleConcordance")
 * )
 *
 * @package App\Models\BibleConcordance
 */
class BibleConcordance extends Model
{
    public $table = 'bible_verse_concordance';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @OA\Property(
     *   title="bible_verse_id",
     *   type="integer",
     *   description=""
     * )
     */
    protected $bible_verse_id;

    /**
     * @OA\Property(
     *   title="key_word",
     *   type="string",
     *   description=""
     * )
     */
    protected $key_word;

    public function verse()
    {
        return $this->belongsTo(BibleVerse::class);
    }

}
