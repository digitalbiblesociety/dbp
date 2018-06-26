<?php
namespace database\seeds;
use App\Models\Organization\Organization;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\Bible;
class SeederHelper
{

	public function csv_to_array($csvfile) {
		$csv = Array();
		$rowcount = 0;
		if (($handle = fopen($csvfile, "r")) !== FALSE) {
			$max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
			$header = fgetcsv($handle, $max_line_length);
			$header_colcount = count($header);
			while (($row = fgetcsv($handle, $max_line_length)) !== FALSE) {
				$row_colcount = count($row);
				if ($row_colcount == $header_colcount) {
					$entry = array_combine($header, $row);
					$csv[] = $entry;
				}
				else {
					error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
					return null;
				}
				$rowcount++;
			}
//echo "Totally $rowcount rows found\n";
			fclose($handle);
		}
		else {
			error_log("csvreader: Could not read CSV \"$csvfile\"");
			return null;
		}
		return $csv;
	}

	public function slug($string)
	{
		$separator = "-";
		$accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
		$special_cases = array( '&' => 'and', "'" => '');
		$string = mb_strtolower( trim( $string ), 'UTF-8' );
		$string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
		$string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
		$string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
		$string = preg_replace("/[$separator]+/u", "$separator", $string);
		return $string;
	}

	public function random_string($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}


	public function seedBibleEquivalents($bibleEquivalents, $partner, $type, $site) {

		$partner = Organization::where('slug',$partner)->first();
		if(!$partner) {
			echo "\n Publisher not found";
			die();
		} else {
			$partner = $partner->id;
		}

		BibleEquivalent::where('site',$site)->where('organization_id',$partner)->delete();

		foreach($bibleEquivalents as $equivalent) {
			$bible = Bible::find($equivalent['bible_id']);
			if(!$bible) {
				echo "\n Missing Bible ID:" . $equivalent['bible_id'];
				continue;
			}
			$alreadyExist = BibleEquivalent::where('equivalent_id',$equivalent['equivalent_id'])->where('organization_id',$partner)->where('site',$site)->first();
			if(!$alreadyExist) {
				BibleEquivalent::create([
					'bible_id'            => $equivalent['bible_id'],
					'equivalent_id'       => $equivalent['equivalent_id'],
					'organization_id'     => $partner,
					'type'                => $type,
					'site'                => $site,
					'suffix'              => (isset($equivalent['suffix'])) ? $equivalent['suffix'] : ''
				]);
			}
		}
	}

	public function tsv_to_collection($file,$args=array()) {
		//key => default
		$fields = array(
			'header_row'=>true,
			'remove_header_row'=>true,
			'trim_headers'=>true, //trim whitespace around header row values
			'trim_values'=>true, //trim whitespace around all non-header row values
			'debug'=>false, //set to true while testing if you run into troubles
			'lb'=>"\n", //line break character
			'tab'=>"\t", //tab character
		);
		foreach ($fields as $key => $default) {
			if (array_key_exists($key,$args)) { $$key = $args[$key]; }
			else { $$key = $default; }
		}

		if (!file_exists($file)) {
			if ($debug) { $error = 'File does not exist: '.htmlspecialchars($file).'.'; }
			else { $error = 'File does not exist.'; }
			dd($error);
		}

		if ($debug) { echo '<p>Opening '.htmlspecialchars($file).'&hellip;</p>'; }
		$data = array();

		if (($handle = fopen($file,'r')) !== false) {
			$contents = fread($handle, filesize($file));
			fclose($handle);
		} else {
			dd('There was an error opening the file.');
		}

		$lines = explode($lb,$contents);
		if ($debug) { echo '<p>Reading '.count($lines).' lines&hellip;</p>'; }

		$row = 0;
		foreach ($lines as $line) {
			$row++;
			if (($header_row) && ($row == 1)) { $data['headers'] = array(); }
			else { $data[$row] = array(); }
			$values = explode($tab,$line);
			foreach ($values as $c => $value) {
				if (($header_row) && ($row == 1)) { //if this is part of the header row
					if (in_array($value,$data['headers'])) { custom_die('There are duplicate values in the header row: '.htmlspecialchars($value).'.'); }
					else {
						if ($trim_headers) { $value = trim($value); }
						$data['headers'][$c] = $value.''; //the .'' makes sure it's a string
					}
				} elseif ($header_row) { //if this isn't part of the header row, but there is a header row
					$key = $data['headers'][$c];
					if ($trim_values) { $value = trim($value); }
					$data[$row][$key] = $value;
				} else { //if there's not a header row at all
					$data[$row][$c] = $value;
				}
			}
		}

		if ($remove_header_row) {
			unset($data['headers']);
		}

		if ($debug) { echo '<pre>'.print_r($data,true).'</pre>'; }
		return collect($data);
	}


	function remote_filesize($url, $fallback_to_download = true)
	{
		static $regex = '/^Content-Length: *+\K\d++$/im';
		if (!$fp = @fopen($url, 'rb')) {
			return false;
		}
		if (isset($http_response_header) && preg_match($regex, implode("\n", $http_response_header), $matches)) {
			return (int)$matches[0];
		}
		if (!$fallback_to_download) {
			return false;
		}
		return strlen(stream_get_contents($fp));
	}

}