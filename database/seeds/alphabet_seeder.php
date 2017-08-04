<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Language\Alphabet;
use App\Models\Language\AlphabetNumber;

class alphabet_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    \DB::connection('geo_data')->table('alphabet_fonts')->delete();
    	\DB::connection('geo_data')->table('alphabet_numbers')->delete();
	    \DB::connection('geo_data')->table('alphabet_language')->delete();
	    \DB::connection('geo_data')->table('alphabets')->delete();
        $seederhelper = new SeederHelper();
        $sheet_id = '1GoBzI4VRP2bQW8LMdv0eJrEICSSAJ-k_8yix3XjBf8w';

        $alphabets = $seederhelper->csv_to_array("https://docs.google.com/spreadsheets/d/$sheet_id/export?format=csv&id=$sheet_id");
        foreach ($alphabets as $alphabet) Alphabet::create($alphabet);

	    $alphabet_numbers = $seederhelper->csv_to_array("https://docs.google.com/spreadsheets/d/$sheet_id/export?format=csv&id=$sheet_id&gid=1908412109");
	    foreach ($alphabet_numbers as $alphabet_number) AlphabetNumber::create($alphabet_number);

    }

}
