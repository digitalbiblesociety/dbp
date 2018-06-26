<?php

use Illuminate\Database\Seeder;

class organizations_address_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$organizations = \App\Models\Organization\Organization::all();
        foreach ($organizations as $organization) {
	        $address = str_replace('  ',' ',$organization->address);
	        $address = str_replace(' ','+', $address);
	        if($address == "") {continue;}
	        $geocoded = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyDtF80a_-CoJOCCQcnO7i8dsFoaNIsVGlo"));
	        if(($geocoded->status == "OK") AND !$organization->longitude) {
		        $geocoded = $geocoded->results[0];
		        $organization->address = $geocoded->formatted_address;
		        $organization->longitude = $geocoded->geometry->location->lng;
		        $organization->latitude = $geocoded->geometry->location->lat;
		        //$organization->country = isset($geocoded->address_components[6]) ? $geocoded->address_components[6]->short_name : null;
		        $organization->save();
	        } else {
	        	echo "\nBroken: ".$organization->id;
	        }

        }
    }
}
