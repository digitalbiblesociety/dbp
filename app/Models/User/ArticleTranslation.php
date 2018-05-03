<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\ArticleTranslation
 *
 * @property int $article_id
 * @property string $iso
 * @property string $name
 * @property string|null $description
 * @property int $vernacular
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ArticleTranslation whereVernacular($value)
 * @mixin \Eloquent
 */
class ArticleTranslation extends Model
{
    //
}
