<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleFile;
use App\Models\Bible\VideoResolution;
use App\Models\Bible\VideoTransportStream;

class bible_file_video_streams extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::transaction(function () {

		    $seederHelper = new \database\seeds\SeederHelper();
		    $resolutions = $seederHelper->csv_to_array(storage_path('data/dbp_dev___10-2-18_stream_res.csv'));
		    $t_stream = $seederHelper->csv_to_array(storage_path('data/dbp_dev___10-2-18_stream_ts.csv'));

	        foreach($resolutions as $resolution) {
	        	$bible_file = BibleFile::where('file_name',$resolution['stream_parent'])->first();
				if(!$bible_file) {continue;}

				$current_resolution = $bible_file->videoResolution()->create([
					'file_name'         => $resolution['filename'],
					'bandwidth'         => $resolution['bandwidth'],
					'resolution_width'  => $resolution['resolution_width'],
					'resolution_height' => $resolution['resolution_height'],
					'codec'             => $resolution['codec'],
					'stream'            => 1,
				]);

				foreach($t_stream as $stream) {

					if($stream['stream_res'] === $resolution['filename']) {
						$current_resolution->transportStream()->create([
							'file_name'  => $stream['filename'],
							'runtime'    => $stream['runtime'],
						]);
					}

				}

	        }
	    });
    }
}
