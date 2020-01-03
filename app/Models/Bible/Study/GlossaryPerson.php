<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

class GlossaryPerson extends Model
{
    protected $connection = 'dbp';
    protected $table = 'glossary_person';
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['id','description'];

    protected $id;
    protected $description;


    public function relationships()
    {
        return $this->belongsToMany(GlossaryPerson::class)->withPivot('relationship_type');
    }

    public function names()
    {
        return $this->hasMany(GlossaryPersonName::class);
    }
}
