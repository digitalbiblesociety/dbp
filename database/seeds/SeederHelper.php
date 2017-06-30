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
			$bible = Bible::find($equivalent['abbr']);
			if(!$bible) {
				echo "\n Missing Bible ID:" . $equivalent['abbr'];
				continue;
			}
			$alreadyExist = BibleEquivalent::where('equivalent_id',$equivalent['equivalent_id'])->where('organization_id',$partner)->where('site',$site)->first();
			if(!$alreadyExist) {
				BibleEquivalent::create([
					'abbr'            => $equivalent['abbr'],
					'equivalent_id'   => $equivalent['equivalent_id'],
					'organization_id' => $partner,
					'type'            => $type,
					'site'            => $site,
					'suffix'          => (isset($equivalent['suffix'])) ? $equivalent['suffix'] : ''
				]);
			}
		}
	}

}