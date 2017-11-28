<?php

namespace App\Transformers;

use App\Models\Bible\BibleFileset;
use League\Fractal\TransformerAbstract;

class FileSetTransformer extends BaseTransformer
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(BibleFileset $fileset)
    {
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($fileset);
		    case "2": return $this->transformForV2($fileset);
		    case "4":
		    default: return $this->transformForV4($fileset);
	    }
    }

    public function transformForDataTables($fileset)
    {
    	return [
		    "<a href='/bibles/filesets/$fileset->id'>$fileset->id</a>",
			$fileset->name,
		    $fileset->set_type,
		    $fileset->organization_id,
		    $fileset->bible_id
	    ];
    }


}
