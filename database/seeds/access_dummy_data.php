<?php

use Illuminate\Database\Seeder;

class access_dummy_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::table('access_group_filesets')->delete();
	    \DB::table('access_group_keys')->delete();
	    \DB::table('access_group_types')->delete();
	    \DB::table('access_groups')->delete();

	    \DB::table('access_type_translations')->delete();
	    \DB::table('access_types')->delete();

    	$groups = [
    		'Vanyar'    => 'The first sundering of the Elves occurred after they were invited to Aman; the ones who were unwilling to leave Middle-earth became known as the avari, meaning unwilling.',
		    'Noldor'    => 'Of those Elves who chose to go to Aman (who, collectively, are called the Eldar), some of them stopped at the Misty Mountains in YT 1115 and went no further; these are the Nandor.',
		    'Silvan'    => 'The Nandor who chose not to cross into Beleriand became known as the Silvan. These are the Wood-Elves referred to in The Hobbit, as well as most residents of Lothlórien',
		    'Laiquendi' => 'The Nandor eventually subdivided further, in YT 1350 when some of them crossed the Misty Mountains, came into Ossiriand, and became the Green-elves',
		    'Sindar'    => 'The final major sundering occurred in YT 1132, when one of the Elven kings, Elwë, became lost and his people stayed behind to look for him. Elwë would later return (and be renamed Elu Thingol) and his people came to be known as the Sindar'
	    ];
    	foreach($groups as $name => $description) {

    		$access_group = \App\Models\User\AccessGroup::create([
				'name'        => $name,
		        'description' => $description
		    ]);

    		// Filesets
		    $filesets = \App\Models\Bible\BibleFileset::inRandomOrder()->take(25)->get();
    		foreach($filesets as $fileset) {
			    \App\Models\User\AccessGroupFileset::create([
					'access_group_id' => $access_group->id,
			        'hash_id'         => $fileset->hash_id,
			    ]);
		    }

		    // Keys
		    $keys = \App\Models\User\Key::inRandomOrder()->take(3)->get();
		    foreach($keys as $key) {
			    \App\Models\User\AccessGroupKey::create([
				    'access_group_id' => $access_group->id,
				    'key_id'          => $key->key
			    ]);
		    }

		    // Types
		    $access_types = ['download','podcast','text','use-limit-2000','use-limit-200'];
		    $i = 0;
		    while($i < 100) {

			    $country_limited = rand(0,1);
			    $continent_limited = (!$country_limited) ? \App\Models\Country\Country::inRandomOrder()->take(1)->first()->continent : null;

			    $allowed = rand(0,1);

			    \App\Models\User\AccessType::create([
				    'name'            => $access_types[rand(0,4)],
				    'country_id'      => ($country_limited) ? \App\Models\Country\Country::inRandomOrder()->take(1)->first()->id : null,
				    'continent_id'    => $continent_limited,
				    'allowed'         => $allowed
			    ]);

		    	$i++;
		    }

		    $i = 0;
		    while($i < 50) {
		    	\DB::table('access_group_types')->insert([
		    		'access_group_id' => \App\Models\User\AccessGroup::inRandomOrder()->first()->id,
				    'access_type_id'  => \App\Models\User\AccessType::inRandomOrder()->first()->id
			    ]);
		    	$i++;
		    }

	    }



    }
}
