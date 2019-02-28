<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * App\Models\User\ArticleTranslation
 *
 * @property int $article_id
 * @property string $iso
 * @property string $name
 * @property string $description
 * @property int $vernacular
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ArticleTranslation whereArticleId($value)
 * @method static ArticleTranslation whereCreatedAt($value)
 * @method static ArticleTranslation whereDescription($value)
 * @method static ArticleTranslation whereIso($value)
 * @method static ArticleTranslation whereName($value)
 * @method static ArticleTranslation whereUpdatedAt($value)
 * @method static ArticleTranslation whereVernacular($value)
 *
 * @mixin \Eloquent
 */
class ArticleTranslation extends Model
{
    protected $connection = 'dbp_users';
    public $fillable = ['iso','name','body'];

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
