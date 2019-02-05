<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\UserSetting
 *
 * @mixin \Eloquent
 *
 * @protected $theme
 * @protected $font_type
 * @protected $font_size
 * @protected $bible_id
 * @protected $book_id
 * @protected $chapter
 * @protected $readers_mode
 * @protected $justified_text
 * @protected $cross_references
 * @protected $one_verse_per_line
 *
 * @OA\Schema (
 *     type="object",
 *     description="",
 *     title="UserSetting",
 *     @OA\Xml(name="UserSetting")
 * )
 *
 */
class UserSetting extends Model
{

    /**
     *
     * @OA\Property(
     *   title="theme",
     *   type="string",
     *   description="The currently activated theme",
     * )
     *
     * @method static UserSetting whereTheme($value)
     * @property string $theme
     */
    protected $theme;

    /**
     *
     * @OA\Property(
     *   title="preferred_font",
     *   type="string",
     *   description="The user's preferred font"
     * )
     *
     * @method static UserSetting wherePreferredFont($value)
     * @property string $preferred_font
     */
    protected $preferred_font;

    /**
     *
     * @OA\Property(
     *   title="font_size",
     *   type="integer",
     *   description="The user's preferred font size"
     * )
     *
     * @method static UserSetting whereFontSize($value)
     * @property integer $font_size
     */
    protected $font_size;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Bible/properties/id")
     * @method static UserSetting whereBibleId($value)
     * @property string $bible_id
     */
    protected $bible_id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Book/properties/id")
     * @method static UserSetting whereBookId($value)
     * @property string $book_id
     *
     */
    protected $book_id;

    /**
     *
     * @OA\Property(
     *   title="chapter",
     *   type="integer",
     *   description="This field in combination with the book_id and bible_id can be used to store a user's last position"
     * )
     *
     * @method static UserSetting whereChapter($value)
     * @property integer $chapter
     */
    protected $chapter;

    /**
     *
     * @OA\Property(
     *   title="readers_mode",
     *   type="boolean",
     *   description="The readers_mode"
     * )
     *
     * @method static UserSetting whereReadersMode($value)
     * @property boolean $readers_mode
     */
    protected $readers_mode;

    /**
     *
     * @OA\Property(
     *   title="justified_text",
     *   type="boolean",
     *   description="The justified_text"
     * )
     *
     * @method static UserSetting whereJustifiedText($value)
     * @property boolean $justified_text
     */
    protected $justified_text;

    /**
     *
     * @OA\Property(
     *   title="cross_references",
     *   type="boolean",
     *   description="Show cross_references & Footnotes"
     * )
     *
     * @method static UserSetting whereCrossReferences($value)
     * @property boolean $cross_references
     */
    protected $cross_references;

    /**
     *
     * @OA\Property(
     *   title="unformatted",
     *   type="boolean",
     *   description="The user prefers their text unformatted if possible"
     * )
     *
     * @method static UserSetting whereUnformatted($value)
     * @property boolean $unformatted
     */
    protected $unformatted;


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
