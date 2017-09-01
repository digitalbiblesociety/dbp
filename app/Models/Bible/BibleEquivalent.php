<?php

namespace App\Models\Bible;

use App\Models\Bible\Bible;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

class BibleEquivalent extends Model
{
    protected $table = "bible_equivalents";
    protected $primaryKey = 'equivalent_id';
    protected $hidden = ['created_at','updated_at','bible_id'];
    protected $fillable = ['abbr','equivalent_id','organization_id','type','suffix'];
    public $incrementing = false;

    public function bible()
    {
        return $this->BelongsTo(Bible::class,'bible_id','id');
    }

    public function organization()
    {
        return $this->HasOne(Organization::class,'id','organization_id');
    }

}
