<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

class Lexicon extends Model
{
    public $incrementing = false;
    public $connection = 'dbp';

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="Strongs id for the lexical entry",
     *   pattern="/^[G||H]\d\d?\d?\d?$/",
     *   example="G1055",
     *   minLength=2,
     *   maxLength=5
     * )
     *
     * @var $id
     */
    protected $id;
    /**
     *
     * @OA\Property(
     *   title="base_word",
     *   type="string",
     *   description="The Greek or Hebrew word being defined",
     *   example="γαλήνη",
     *   maxLength=64
     * )
     *
     * @var $base_word
     */
    protected $base_word;
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The way the word is commmonly translated in English Bibles",
     *   examples="calm",
     * )
     *
     * @var $usage
     */
    protected $usage;
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The USFM 2.4 id for the books of the Bible",
     * )
     *
     * @var $definition
     */
    protected $definition;
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The USFM 2.4 id for the books of the Bible",
     * )
     *
     * @var $derived
     */
    protected $derived;
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The USFM 2.4 id for the books of the Bible",
     * )
     *
     * @var $part_of_speech
     */
    protected $part_of_speech;
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The USFM 2.4 id for the books of the Bible",
     * )
     *
     * @var $aramaic
     */
    protected $aramaic;
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The USFM 2.4 id for the books of the Bible",
     * )
     *
     * @var $comment
     */
    protected $comment;

    public function scopeFilterByLanguage($query, $language)
    {
        $query->when($language, function ($query, $language) {
            return $query->where('id','LIKE', $language.'%');
        });
    }

    public function scopeFilterByWord($query, $word, $exact_match)
    {
        $query->when($word, function ($query, $word, $exact_match) {
            return $query->whereHas('definitions', function ($subquery) use($word, $exact_match) {
                if(!$exact_match) {
                    $subquery->where('definition', $word);
                } else {
                    $subquery->where('definition', 'like', '%'.$word.'%');
                }
            });
        });
    }

}
