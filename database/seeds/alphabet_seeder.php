<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Language\Alphabet;
class alphabet_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederhelper = new SeederHelper();
        $alphabets = $seederhelper->csv_to_array(storage_path() . "/data/languages/alphabets.csv");
        foreach ($alphabets as $key => $alphabet) Alphabet::insert($alphabet);
    }

}
