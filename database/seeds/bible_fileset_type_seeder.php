<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleFilesetType;
class bible_fileset_type_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileset_types = [
        	['set_type_code' => 'NT',    'name' => 'New Testament'],
	        ['set_type_code' => 'OT',    'name' => 'Old Testament'],
	        ['set_type_code' => 'NTOTP', 'name' => 'New Testament with Old Testament Portions'],
	        ['set_type_code' => 'OTNTP', 'name' => 'Old Testament with New Testament Portions'],
	        ['set_type_code' => 'NTP',   'name' => 'New Testament Portions'],
	        ['set_type_code' => 'OTP',   'name' => 'Old Testament Portions'],
	        ['set_type_code' => 'C',     'name' => 'Complete Bible'],
        ];
		foreach ($fileset_types as $fileset_type) BibleFilesetType::create($fileset_type);
    }
}
