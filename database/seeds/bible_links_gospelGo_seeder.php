<?php

use Illuminate\Database\Seeder;

class bible_links_gospelGo_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = json_decode(file_get_contents(storage_path('templanglist.json')));
        foreach($languages as $language) {
        	$current_language = Language::where('name',$language)->first();
        	if($current_language) { echo "\n".$current_language->iso; continue;}



        	echo "\n";
        }
    }
}
