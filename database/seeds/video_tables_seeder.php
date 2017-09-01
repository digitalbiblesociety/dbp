<?php

use Illuminate\Database\Seeder;

class video_tables_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$videos = \DB::connection('deafBible')->table('bible_videos')->get();

		dd($videos->first());
    }
}
