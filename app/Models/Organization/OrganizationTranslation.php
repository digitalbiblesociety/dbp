<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationTranslation extends Model
{
    protected $primaryKey = 'organization_id';
    protected $fillable = ['iso', 'name','description'];
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','organization_id','description'];

    public function organization()
    {
        return $this->BelongsTo(Organization::class);
    }

}
