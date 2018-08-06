<?php

namespace App\Http\Controllers\Bible;

use App\Jobs\ProcessAudioBibles;
use Illuminate\Http\Request;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Vorbis;
use App\Http\Controllers\APIController;

class AudioProcessingController extends Controller
{

	public function index()
	{
		return view('bibles.audio.uploads.index');
	}

	public function create()
	{
		return view('bibles.audio.uploads.create');
	}

	public function store(Request $request)
	{
		//Storage::put('uploads/audioBibles', $request->bible_zip);
		if (!is_dir(public_path('uploads/' . $request->bible_id))) {
			mkdir(public_path('/uploads/' . $request->bible_id));
		}
		//$request->bible_zip->store('images');
		//$request->bible_zip
		$pathToAudio = public_path("/uploads/dota.mp3");
		$ffmpeg      = FFMpeg::create([
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

		$audio = $ffmpeg->open($pathToAudio);
		$audio->filters()->addMetadata();
		$audio->filters()->addMetadata(["title" => "DOTA", "track" => 1]);
		$audio->filters()->addMetadata(["description" => "A techno song about a popular Game"]);
		$audio->save($mp3Format, public_path('uploads/dota_refactored.mp3'));
		$ffmpeg->open($pathToAudio)->save($oggFormat, public_path('uploads/dota_refactored.ogg'));

		return redirect()->route('bibles_audio_uploads.thanks');
	}

	public function thanks()
	{
		$compliments = @json_decode(file_get_contents(storage_path() . '/data/site/compliments.json'));
		$compliment  = @$compliments[array_rand($compliments)];

		return view('bibles.audio.uploads.thanks', compact('compliment'));
	}

	public function readId3()
	{

	}
}
