<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Language\Language;
class language_primaryCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $languages = $seederHelper->csv_to_array(storage_path().'/data/languages/language_codes.csv');

        foreach($languages as $language) {
            Language::where('iso','=',$language['LangID'])->update(['country_id' => $language['CountryID']]);
        }

    }
}
