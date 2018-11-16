<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\ArticleTag
 *
 * @property int $article_id
 * @property string $iso
 * @property string $tag
 * @property string $name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static ArticleTag whereArticleId($value)
 * @method static ArticleTag whereCreatedAt($value)
 * @method static ArticleTag whereDescription($value)
 * @method static ArticleTag whereIso($value)
 * @method static ArticleTag whereName($value)
 * @method static ArticleTag whereTag($value)
 * @method static ArticleTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleTag extends Model
{
    protected $connection = 'dbp_users';
    protected $fillable = ['iso','name','description'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
