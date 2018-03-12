<?php

namespace App\Console\Commands;

use App\Helpers\AWS\Bucket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class fetch_s3_audio_length extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:s3_audio_length';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    // $mp3File = "https://dbp-dev.s3.us-west-2.amazonaws.com/audio/AFRABY/AFRNVVP2DA/A19__001_Psalms______AFRNVVP2DA.mp3?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJOMYRUAEFXAK5KDQ%2F20180312%2Fus-west-2%2Fs3%2Faws4_request&X-Amz-Date=20180312T123926Z&X-Amz-SignedHeaders=host&X-Amz-Expires=12000&X-Amz-Signature=6225b9ba509e92cb7c14b5b786f6e19f4c4e1321b0949cf7d70052881d3f6486";
	    // $waveform = $probe->format($mp3File);
	    // dd($waveform);
	    $disk = 's3_fcbh';
	    $audio_files = Storage::disk($disk)->files("audio/AFRABY/AFRNVVP2DA");
	    foreach($audio_files as $audio_file) {
	    	// $audio_file = Storage::disk($disk)->get($audio_file);
		    $audio_file_url = Bucket::signedUrl($audio_file);
		    //$audio_file_url = "https://downloads.dbs.org/treasures/bengali/Bible/StudyBible/content/audio/WBTCBEN/40_Matthew_01.mp3";
		    $output = shell_exec("ffprobe -i ".$audio_file_url);
		    dd($output);
	    }
	    // $output = shell_exec("ffmpeg -i ");


    }
}
