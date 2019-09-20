<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lexicon
 *
 *  @OA\Schema (
 *    type="object",
 *    description="Lexicon",
 *    title="Lexicon",
 *    @OA\Xml(name="Lexicon")
 * )
 *
 * @package App\Models\Bible\Study
 */
class Lexicon extends Model
{
    public $incrementing = false;
    public $connection = 'dbp';
    protected $fillable = ['id', 'base_word', 'usage', 'definition', 'derived', 'part_of_speech', 'aramaic', 'comment'];
    protected $keyType = 'string';

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
     *   title="usage",
     *   type="string",
     *   description="",
     *   example="calm",
     * )
     *
     * @var $usage
     */
    protected $usage;

    /**
     *
     * @OA\Property(
     *   title="definition",
     *   type="string",
     *   description="",
     * )
     *
     * @var $definition
     */
    protected $definition;

    /**
     *
     * @OA\Property(
     *   title="derived",
     *   type="string",
     *   description="",
     * )
     *
     * @var $derived
     */
    protected $derived;

    /**
     *
     * @OA\Property(
     *   title="part_of_speech",
     *   type="string",
     *   description="",
     * )
     *
     * @var $part_of_speech
     */
    protected $part_of_speech;

    /**
     *
     * @OA\Property(
     *   title="aramaic",
     *   type="string",
     *   description="",
     * )
     *
     * @var $aramaic
     */
    protected $aramaic;

    /**
     *
     * @OA\Property(
     *   title="comment",
     *   type="string",
     *   description="",
     * )
     *
     * @var $comment
     */
    protected $comment;

    public function definitions()
    {
        return $this->hasMany(LexicalDefinition::class);
    }

    public function scopeFilterByLanguage($query, $language)
    {
        $query->when($language, function ($query, $language) {
            return $query->where('id', 'LIKE', $language.'%');
        });
    }

    public function scopeFilterByWord($query, $word, $exact_match)
    {
        $query->when($word, function ($query) use ($word, $exact_match) {
            return $query->whereHas('definitions', function ($subquery) use ($word, $exact_match) {
                if (!$exact_match) {
                    $subquery->where('definition', $word);
                } else {
                    $subquery->where('definition', 'like', '%'.$word.'%');
                }
            });
        });
    }
}
