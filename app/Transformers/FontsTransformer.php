<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class FontsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform()
    {
        return [

	        /*
			 * [
	 {
	 "id": "9",
	 "name": "More Fonty Goodness",
	 "base_url": "http://cloud.faithcomesbyhearing.com/fonts-stage/More_Fonty_Goodness",
	 "files": {
	 "zip": "all.zip",
	 "svg": "font.svg",
	 "ttf": "font.ttf"
	 },
	 "platforms": {
	 "android": true,
	 "ios": true,
	 "web": true
	 }
	 },
	 {
	 "id": "11",
	 "name": "Charis SILR",
	 "base_url": "http://cloud.faithcomesbyhearing.com/fonts-stage/Charis_SILR",
	 "files": {
	 "zip": "all.zip",
	 "ttf": "font.ttf"
	 },
	 "platforms": {
	 "android": true,
	 "ios": true,
	 "web": true
	 }
	 }
	 ]

			 */
        ];
    }
}
