<?php

namespace App\Jobs;

use Exception;

use FFMpeg\Format\Audio\Vorbis;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Http\Request;
class ProcessAudioBibles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
    	if(!is_dir(public_path().'/uploads/'.$request->bible_id)) mkdir(public_path().'/uploads/'.$request->bible_id);
	    $request->bible_zip->store('images');
		//$request->bible_zip
	    $pathToAudio = public_path("/uploads/dota.mp3");
	    $ffmpeg = FFMpeg::create([
		    'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg', // the path to the FFMpeg binary
		    'ffprobe.binaries' => '/usr/local/bin/ffprobe', // the path to the FFProbe binary
		    'timeout'          => 3600, // the timeout for the underlying process
		    'ffmpeg.threads'   => 12,   // the number of threads that FFMpeg should use
	    ]);

	    // We're outputting mp3 with rather aggressive audio compression
	    // but since this is meant for audio books, it should be fine.
	    $mp3Format = new Mp3();
	    $oggFormat = new Vorbis();

	    $mp3Format->setAudioChannels(1)->setAudioKiloBitrate(32);
	    $oggFormat->setAudioChannels(1)->setAudioKiloBitrate(32);

	    $audio = $ffmpeg->open( $pathToAudio );
	    $audio->filters()->addMetadata();
	    $audio->filters()->addMetadata(["title" => "", "track" => 1]);
	    $audio->filters()->addMetadata(["description" => ""]);
	    $audio->save($mp3Format, public_path().'/uploads/dota_refactored.mp3');
	    $audio->save($oggFormat, public_path().'/uploads/dota_refactored.ogg');

    }

	/**
	 * The job failed to process.
	 *
	 * @param  Exception  $exception
	 * @return void
	 */
	public function failed(Exception $exception)
	{
		// Send user notification of failure, etc...
	}

}
