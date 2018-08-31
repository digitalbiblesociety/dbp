<?php

namespace App\Transformers;

use App\Models\Language\AlphabetNumber;
use App\Models\Language\AlphabetNumeralSystem;
use League\Fractal\TransformerAbstract;

class NumbersTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($numeral_system)
    {
		switch ($this->version) {
			case "2":
			case "3": return $this->transformForV2($numeral_system);
		    case "4":
		    default: return $this->transformForV4($numeral_system);
		}
    }

    public function transformForV2($numeral_system) {
    	return $numeral_system->toArray();
    }

    public function transformForV4($numeral_system) {
    	switch ($this->route) {
		    case "v4_numbers.all": {
			    return [
				    'id'          => $numeral_system->id,
				    'description' => $numeral_system->description,
				    'notes'       => $numeral_system->notes,
				    'alphabets'   => $numeral_system->alphabets->pluck('name','script')
			    ];
		    }
		    case "v4_numbers.one": {
			    return [
				    'id'          => $numeral_system->id,
				    'description' => $numeral_system->description,
				    'notes'       => $numeral_system->notes,
				    'alphabets'   => $numeral_system->alphabets->pluck('name','script'),
				    'numerals'    => $numeral_system->numerals
			    ];
		    }
	    }

    }

}
