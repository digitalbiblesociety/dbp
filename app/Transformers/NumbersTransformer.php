<?php

namespace App\Transformers;

use App\Models\Language\AlphabetNumber;
use League\Fractal\TransformerAbstract;

class NumbersTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = checkParam('v', null, 'optional') ?? 4;
		$this->iso = checkParam('iso', null, 'optional') ?? "eng";
	}
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(AlphabetNumber $alphabet_number)
    {
		switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($alphabet_number);
		    case "2": return $this->transformForV2($alphabet_number);
		    case "4":
		    default: return $this->transformForV4($alphabet_number);
		}
    }

    public function transformForDataTables($alphabet_number) {
	    return $alphabet_number->toArray();
    }

    public function transformForV2($alphabet_number) {
    	return $alphabet_number->toArray();
    }

    public function transformForV4($alphabet_number) {
	    return $alphabet_number->toArray();
    }

}
