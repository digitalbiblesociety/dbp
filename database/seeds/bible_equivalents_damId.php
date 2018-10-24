<?php

use Illuminate\Database\Seeder;

class bible_equivalents_damId extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = \App\Models\Bible\BibleFilesetTag::where('name', 'sku')->get();
	    $oldVolumes = \DB::table('dam_library')->select(['dam_id','fcbh_id','dam_id_root','stocknumber'])->get();
        $language = \App\Models\Language\Language::where('iso','und')->first();
        foreach($tags as $tag) {
        	$current_fileset = $oldVolumes->where('stocknumber',$tag->description)->first();
        	if(!\App\Models\Bible\BibleFileset::where('hash_id',$tag->hash_id)->exists()) {echo "\n couldn't find ". $tag->description;continue;}
        	if($current_fileset) {
		        \App\Models\Bible\BibleFilesetTag::create([
			        'name'          => 'fcbh_dam_id',
			        'hash_id'       => $tag->hash_id,
			        'description'   => $current_fileset->dam_id,
			        'admin_only'    => 0,
			        'language_id'   => $language->id
		        ]);
	        } else {
        		echo "\n couldn't find ". $tag->description;
	        }

        }
    }
}
