<?php

namespace App\Transformers;

class NumbersTransformer extends BaseTransformer
{
	/**
	 * A Fractal transformer.
	 *
	 * @param $numeral_system
	 *
	 * @return array
	 */
    public function transform($numeral_system)
    {
		switch ($this->version) {
			case 2:
			case 3: return $this->transformForV2($numeral_system);
		    case 4:
		    default: return $this->transformForV4($numeral_system);
		}
    }

    public function transformForV2($numeral_system) {
    	return $numeral_system->toArray();
    }

    public function transformForV4($numeral_system) {
    	switch ($this->route) {
		    case 'v4_numbers.one': {
			    return [
				    'id'          => $numeral_system->id,
				    'description' => $numeral_system->description,
				    'notes'       => $numeral_system->notes,
				    'alphabets'   => $numeral_system->alphabets->pluck('name','script'),
				    'numerals'    => $numeral_system->numerals
			    ];
		    }

		    case 'v4_numbers.all':
		    default: {
			    return [
				    'id'          => $numeral_system->id,
				    'description' => $numeral_system->description,
				    'notes'       => $numeral_system->notes,
				    'alphabets'   => $numeral_system->alphabets->pluck('name','script')
			    ];
		    }

	    }

    }

}
