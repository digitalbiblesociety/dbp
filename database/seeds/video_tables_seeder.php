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
	    \DB::table('video_sources')->delete();
    	\DB::table('videos')->delete();
		$videos = \DB::connection('deafBible')->table('bible_videos')->get();
	    $video_type_references = \DB::connection('deafBible')->table('bible_video_type_references')->get();
	    $bible_video_types = \DB::connection('deafBible')->table('bible_video_types')->get();
	    $bible_video_relationships = \DB::connection('deafBible')->table('bible_video_relationships')->get();

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
		    $film->picture = $video->picture;
		    $film->save();

		    $film->sources()->create([
				'url'        => $video->vimeo_link,
			    'url_type'   => 'Vimeo',
			    'resolution' => 'SD',
			    'encoding'   => 'mp4'
		    ]);

		    $title = $bible_video_types->where('id',$video->type_id)->first();

		    if($title) {
			    $tag = new \App\Models\Bible\VideoTag();
			    $tag->video_id      = $film->id;
			    $tag->category      = 'titles';
				$tag->tag_type      = 'title';
				$tag->tag           = $title->name;
				$tag->language_id   = \App\Models\Language\Language::where('iso','eng')->first()->id;
				$tag->save();
		    }

		    $references = $video_type_references->where('type_id','=',$video->type_id)->all();
		    foreach($references as $reference) {

		    	$book = \App\Models\Bible\Book::where('id_usfx',$reference->book_id)->first();
		    	if(!$book) dd($reference);
		    	$readable_reference = $book->name." ".$reference->chapter_start;
		    	if($reference->chapter_end) $readable_reference .= "-".$reference->chapter_end;
			    $readable_reference .= ":".$reference->verse_start;
			    if($tag->verse_end) $readable_reference .= "-".$reference->verse_end;

			    $tag = new \App\Models\Bible\VideoTag();
			    $tag->video_id      = $film->id;
			    $tag->category      = 'references';
			    $tag->tag_type      = 'reference';
			    $tag->tag           = $readable_reference;
			    $tag->language_id   = \App\Models\Language\Language::where('iso','eng')->first()->id;
			    $tag->book_id       = $book->id;
			    $tag->chapter_start = $reference->chapter_start;
			    $tag->verse_start   = $reference->verse_start;
			    $tag->chapter_end   = $reference->chapter_end;
				$tag->verse_end     = $reference->verse_end;
			    $tag->save();
		    }
	    }

	    // Need to increment again for relationships
	    //foreach ($videos as $video) {
		//    $relationships = $bible_video_relationships->where('parent',$video->vimeo_id)->orWhere('child',$video->vimeo_id)->get();
		//    if($relationships) {}
	    //}

    }
}
