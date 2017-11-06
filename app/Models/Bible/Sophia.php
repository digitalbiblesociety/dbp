<?php

namespace App\Models\Bible;

use App\Models\User\User;
use App\Models\Bible\Bible;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Sophia
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibles
 * @property-read \App\Models\User\User $user
 * @mixin \Eloquent
 */
class Sophia extends Model
{
    /**
     * Name of the table this model relates to
     *
     * @var string
     */
    protected $table = 'inScriptSites';

    /**
     * Values that the User can change
     *
     * @var array
     */
    protected $fillable = [
        "websiteUrl",
        "organization",
        "logo",
        "bannerColor",
        "description",
        "preferredLanguageIso",
        "window1Type", "window1TextID", "window1Value",
        "window2Type", "window2TextID", "window2Value",
        "window3Type", "window3TextID", "window3Value",
        "newWindowType",
        "newWindowTextID",
        "newWindowValue",
        "apiARC", "apiDBP", "apiABS"
    ];

    /**
     * Each Sophia instance has many Bibles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bibles()
    {
        return $this->hasMany(Bible::class);
    }

    /**
     * Each Sophia instance has many Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}