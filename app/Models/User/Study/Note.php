<?php

namespace App\Models\User\Study;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * App\Models\User\Note
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $book_id
 * @property int $chapter
 * @property int $verse_start
 * @property int|null $verse_end
 * @property string $user_id
 * @property string $bible_id
 * @property string|null $reference_id
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Note's model",
 *     title="Note",
 *     @OA\Xml(name="Note")
 * )
 *
 */
class Note extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'user_notes';
    protected $hidden = ['user_id'];
    protected $fillable = [
    'id',
    'v2_id',
    'user_id',
    'bible_id',
    'book_id',
    'chapter',
    'verse_start',
    'verse_end',
    'notes',
    'created_at',
    'updated_at'
  ];
    protected $appends = ['bible_name'];

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The unique incrementing id for each NoteTag",
     *   minimum=0
     * )
     *
     * @method static Note whereId($value)
     */
    protected $id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Book/properties/id")
     * @method static Note whereBookId($value)
     */
    protected $book_id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
     * @method static Note whereChapter($value)
     */
    protected $chapter;

    /**
     *
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
     * @method static Note whereVerseStart($value)
     */
    protected $verse_start;

    /**
     *
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_end")
     * @method static Note whereVerseEnd($value)
     */
    protected $verse_end;

    /**
     *
     * @OA\Property(ref="#/components/schemas/User/properties/id")
     * @method static Note whereUserId($value)
     */
    protected $user_id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Bible/properties/id")
     * @method static Note whereBibleId($value)
     */
    protected $bible_id;

    /**
     *
     * @OA\Property(
     *   title="reference_id",
     *   type="string",
     *   description="The unique incrementing id for each NoteTag"
     * )
     *
     * @method static Note whereReferenceId($value)
     */
    protected $reference_id;

    /**
     *
     * @OA\Property(
     *   title="notes",
     *   type="string",
     *   description="The body of the notes",
     *   nullable=true
     * )
     *
     * @method static Note whereNotes($value)
     */
    protected $notes;

    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The timestamp the note was created at"
     * )
     *
     * @method static Note whereCreatedAt($value)
     */
    protected $created_at;

    /**
     *
     * @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp the Note was last updated at",
     *   nullable=true
     * )
     *
     * @method static Note whereUpdatedAt($value)
     */
    protected $updated_at;

    public function getNotesAttribute($note)
    {
        try {
            return Crypt::decrypt($note);
        } catch (DecryptException $e) {
            return '';
        }
    }

    /**
     *
     * @property-read User $user
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     * @property-read AnnotationTag[] $tags
     *
     */
    public function tags()
    {
        return $this->hasMany(AnnotationTag::class, 'note_id', 'id');
    }

    public function book()
    {
        return $this->hasOne(BibleBook::class, 'book_id', 'book_id')->where(
      'bible_id',
      $this['bible_id']
    );
    }

    /**
     * @OA\Property(
     *   property="verse_text",
     *   title="verse_text",
     *   type="string",
     *   description="The text of the Bible Verse"
     * )
     */
    public function getVerseTextAttribute()
    {
        $note = $this->toArray();
        $chapter = $note['chapter'];
        $verse_start = $note['verse_start'];
        $verse_end = $note['verse_end'] ? $note['verse_end'] : $verse_start;
        $bible = Bible::where('id', $note['bible_id'])->first();
        $fileset = BibleFileset::join(
      'bible_fileset_connections as connection',
      'connection.hash_id',
      'bible_filesets.hash_id'
    )
      ->where('bible_filesets.set_type_code', 'text_plain')
      ->where('connection.bible_id', $bible->id)
      ->first();
        if (!$fileset) {
            return '';
        }
        $verses = BibleVerse::withVernacularMetaData($bible)
      ->where('hash_id', $fileset->hash_id)
      ->where('bible_verses.book_id', $note['book_id'])
      ->where('verse_start', '>=', $verse_start)
      ->where('verse_end', '<=', $verse_end)
      ->where('chapter', $chapter)
      ->orderBy('verse_start')
      ->select(['bible_verses.verse_text'])
      ->get()
      ->pluck('verse_text');
        return implode(' ', $verses->toArray());
    }

    /**
     * @OA\Property(
     *   property="bible_name",
     *   title="bible_name",
     *   type="string",
     *   description="Bible name"
     * )
     */
    public function getBibleNameAttribute()
    {
        $bible = Bible::whereId($this['bible_id'])->with(['translations', 'books.book'])->first();
        $ctitle = optional($bible->translations->where('language_id', $GLOBALS['i18n_id'])->first())->name;
        $vtitle = optional($bible->vernacularTranslation)->name;
        return ($vtitle ? $vtitle : $ctitle);
    }
}
