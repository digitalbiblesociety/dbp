<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;

class bible_images_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $bibleImages = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pmsOFqOMFX2efnx521XVinGtbzybuixJW7PsATCGUI0/export?format=csv&id=1pmsOFqOMFX2efnx521XVinGtbzybuixJW7PsATCGUI0');
        dd($bibleImages);
        // Seeder/Helper


    }
}
