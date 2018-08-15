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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleTag extends Model
{
	protected $connection = 'dbp_users';
}
