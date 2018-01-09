<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Note
 *
 * @property string $user_id
 * @property string $bible_id
 * @property string $project_id
 * @property string|null $reference_id
 * @property string|null $highlights
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereHighlights($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereUserId($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    protected $table = "user_notes";
    protected $fillable = ['user_id','bible_id','reference_id','highlights','notes'];

}
