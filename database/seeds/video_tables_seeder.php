<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\Video;
class video_tables_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::table('videos')->delete();
		$videos = \DB::connection('deafBible')->table('bible_videos')->get();
	    foreach($videos as $video) {

	    	$bible = \App\Models\Bible\Bible::find($video->bible_id);
	    	if(!$bible) {continue;}

	    	if(($video->type_video == "story") | ($video->type_video == "verse")) $video->type_video = "main";

	    	$film = new Video();
	    	$film->bible_id = $video->bible_id;
	    	$film->series = substr($video->type_id,0,3);
		    $film->episode = intval(substr($video->type_id,3));
		    $film->section = $video->type_video;
		    $film->duration = $video->duration;
		    $film->url = $video->vimeo_link;
		    $film->url_download = null;
		    $film->save();
	    }
    }
}
