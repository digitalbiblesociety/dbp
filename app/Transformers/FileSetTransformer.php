<?php

namespace App\Transformers;

use App\Models\Bible\Audio;

class FileSetTransformer extends BaseTransformer
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
	public function transform($audio)
	{
		switch ($this->version) {
			case "jQueryDataTable": return $this->transformForDataTables($audio);
			case "2":
			case "3": return $this->transformForV2($audio);
			case "4":
			default: return $this->transformForV4($audio);
		}
	}

	public function transformForV2($audio) {
		switch($this->route) {
			case "v2_audio_timestamps": {
				return [
					"verse_id"    => (string) $audio->verse_start,
					"verse_start" => $audio->timestamp
				];
			}

			case "v2_audio_path": {
				return [
					"book_id"    => ucfirst(strtolower($audio->book->id_osis)),
					"chapter_id" => (string) $audio->chapter_start,
					"path"       => $audio->file_name
				];
			}

		}
	}

	public function transformForV4($fileset) {

		switch($this->route) {

			case "v4_bible_filesets.podcast": {
				$bible = $fileset->bible->first();
				if(!$bible) return $this->replyWithError("No Bible has been attached to this fileset");

				if(!isset($fileset->ietf_code)) {
					$ietf_code = (isset($bible->language->iso1)) ? $bible->language->iso1 : $bible->language->iso;
					$ietf_code .= '-';
					$ietf_code .= (isset($bible->fileset->primaryCountry)) ? $bible->fileset->primaryCountry : $bible->language->primaryCountry;
				} else {
					$ietf_code = $fileset->ietf_code;
				}


				$meta['channel']['title'] = $bible->translations->where('iso',$bible->iso)->first()->name.' - '.$bible->language->name ?? $bible->where('iso',"eng")->first()->name.' - '.$bible->language->name;
				$meta['channel']['link'] = env('APP_URL_PODCAST') ?? "https://bible.is/";
				$meta['channel']['atom:link']['_attributes'] = ['href'  => 'http://www.faithcomesbyhearing.com/feeds/audio-bibles/'.$bible->id.'.xml','rel'   => 'self','type'  => 'application/rss+xml'];
				$meta['channel']['description'] = $bible->translations->where('iso',$bible->iso)->first()->description ?? $bible->where('iso',"eng")->first()->description;
				$meta['channel']['language'] = $bible->language->iso;
				$meta['channel']['managingEditor'] = 'adhooker@fcbhmail.org';
				$meta['channel']['webMaster'] = 'charles@faithcomesbyhearing.com';
				$meta['channel']['copyright'] = $bible->copyright;
				// $meta['channel']['lastBuildDate'] = ($bible->last_updated) ? $bible->last_updated->toRfc2822String() : "";
				// $meta['channel']['pubDate'] = ($bible->date) ? $bible->date->toRfc2822String() : "";
				$meta['channel']['docs'] = 'http://blogs.law.harvard.edu/tech/rss';
				$meta['channel']['webMaster'] = env('APP_SITE_CONTACT') ?? "";
				$meta['channel']['itunes:keywords'] = 'Bible, Testament, Jesus, Scripture, Holy, God, Heaven, Hell, Gospel, Christian, Bible.is, Church';
				$meta['channel']['itunes:author'] = 'Faith Comes By Hearing';
				$meta['channel']['itunes:subtitle'] = 'Online Audio Bible Recorded by Faith Comes By Hearing';
				$meta['channel']['itunes:explicit'] = 'no';
				$meta['channel']['itunes:owner']['itunes:name'] = 'Faith Comes By Hearing';
				$meta['channel']['itunes:owner']['itunes:email'] = 'jon@dbs.org';
				$meta['channel']['itunes:image'] = ['href' => 'http://bible.is/ImageSize300X300.jpg'];
				$meta['channel']['itunes:category'] = [
					'_attributes' => ['text' => 'Religion & Spirituality']
				];

				$meta['channel']['managingEditor'] = env('APP_SITE_CONTACT') ?? "";
				$meta['channel']['image']['_attributes'] = [
					'url'   => 'http://bible.is/'.$fileset->id.'.jpg',
					'title' => 'Title or description of your logo',
					'link'  => 'http://bible.is',
				];
				$meta['channel']['atom:link'] = [
					'href' => 'http://bible.is/feed.xml',
					'rel'  => 'self',
					'type' => 'application/rss+xml'
				];
				$meta['channel']['pubDate'] = 'Sun, 01 Jan 2012 00:00:00 EST';

				$meta['channel']['itunes:summary'] = 'Duplicate of above verbose description.';
				$meta['channel']['itunes:subtitle'] = 'Short description of the podcast - 255 character max.';

				$items = [];
				$xml_safe_expression = '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u';
				foreach($fileset->files as $file) {
					$bookName = ($file->book->translation($bible->iso)->first()) ? $file->book->translation($bible->iso)->first()->name : $file->book->translation("eng")->first()->name;
					$items[] = [
						'title'       => $bookName.' '.$file->chapter_start,
						'link'        => 'http://podcastdownload.faithcomesbyhearing.com/mp3.php/'.$file->set_id.'/'.$file->file_name,
						'guid'        => 'http://podcastdownload.faithcomesbyhearing.com/mp3.php/'.$file->set_id.'/'.$file->file_name,
						//'description' => ($file->currentTitle) ? htmlspecialchars($file->currentTitle->title) : "",
						'enclosure'   => [
							'name'   => "name",
							'_attributes' => [
								'url'    => 'http://podcastdownload.faithcomesbyhearing.com/mp3.php/ENGESVC2DA/'. $file->file_name .'.mp3',
								'length' => '1703936',
								'type'   => 'audio/mpeg'
							],
						],
						'pubDate'              => 'Wed, 30 Dec 2009 22:22:16 -0700',
						'itunes:author'        => 'Faith Comes By Hearing',
						'itunes:explicit'      => 'no',
						'itunes:subtitle'      =>  ($file->currentTitle) ? preg_replace ($xml_safe_expression, ' ', $file->currentTitle->title) : "",
						'itunes:summary'       =>  ($file->currentTitle) ? preg_replace ($xml_safe_expression, ' ', $file->currentTitle->title) : "",
						'itunes:duration'      => '3:15',
						'itunes:keywords'      => 'Bible, Testament, Jesus, Scripture, Holy, God, Heaven, Hell, Gospel, Christian, Bible.is, Church'
					];
				}
				$meta['channel']['item'] = $items;
				return $meta;
			}

		}
		if($fileset->bible) {
			$bible = $fileset->bible->first();
			$bookName = "";
			if($bible) {
				$books = $bible->books->where('book_id',$fileset->book_id)->first();
				if($books) $bookName = $books->name;
			}
		} else {
			$bookName = $fileset->book->name;
		}
		return [
			"book_id"       => $fileset->book_id,
			"book_name"     => $bookName,
			"chapter_start" => $fileset->chapter_start,
			"chapter_end"   => $fileset->chapter_end,
			"verse_start"   => $fileset->verse_start,
			"verse_end"     => $fileset->verse_end,
			"timestamp"     => $fileset->timestamp,
			"path"          => $fileset->file_name,
		];
	}


}
