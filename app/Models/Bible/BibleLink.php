<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BibleLink
 *
 * handles the links to the different partners
 *
 * @package App\Models\Bible
 */
class BibleLink extends Model
{
    /**
     * BibleLinks will only be called from the Bibles Model. So we don't need ID or Abbr.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at','id','abbr'];
    /**
     * Values the User can Edit
     *
     * @var array
     */
    protected $fillable = ['link', 'type', 'organization_id','url','title'];

    /**
     * The Organization who Provides that link [not necessarily the publisher]
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organization()
    {
        return $this->HasOne(Organization::class, 'id');
    }
}