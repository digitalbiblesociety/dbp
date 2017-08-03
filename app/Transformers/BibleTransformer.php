<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Bible\Bible;
class BibleTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->version = $_GET['v'] ?? 4;
		$this->iso = $_GET['iso'] ?? "eng";
	}

	/**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Bible $bible)
    {
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($bible);
		    case "2": return $this->transformForV2($bible);
		    case "4":
		    default: return $this->transformForV4($bible);
	    }
    }

    public function transformForV2($bible)
    {
    	if("is ") {

    		/*
    		 * 	            "organization_id": "1",
                "organization": "Faith Comes By Hearing",
                "organization_english": "Hosanna Ministries",
                "organization_role": "holder",
                "organization_url": "http://www.faithcomesbyhearing.com",
                "organization_donation": "http://www.faithcomesbyhearing.com/donate",
                "organization_address": null,
                "organization_address2": null,
                "organization_city": null,
                "organization_state": null,
                "organization_country": null,
                "organization_zip": null,
                "organization_phone": null
    		 *
    		 */

		    return [
			    "dam_id"         => $bible->id,
                "mark"           => $bible->copyright,
                "volume_summary" => $bible->translations("eng")->description,
                "font_copyright" => null,
                "font_url"       => null,
                "organization"   => $bible->organization
            ];

	    }

    	return [

	    ];
    }


}
