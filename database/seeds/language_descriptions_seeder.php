<?php

use Illuminate\Database\Seeder;

class language_descriptions_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translations = glob(storage_path('/data/languages/descriptions/*'));
        foreach($translations as $iso_path) {
            $languages = glob($iso_path.'/*');
            $parsing_language = \App\Models\Language\Language::where('iso',basename($iso_path))->first();
            foreach ($languages as $language) {
                $current_language = json_decode(file_get_contents($language));
                $description = @collect($current_language->query->pages)->first()->extract;
                if($description) {
                    $description = preg_replace('/\.?.*?redirects here\. /m','',$description);
                    $description = preg_replace('/\(.*?\) /m','',$description);
                    $current_language = \App\Models\Language\Language::where('iso',basename($language,'.json'))->first();
                    $translation = \App\Models\Language\LanguageTranslation::where('language_translation_id',$parsing_language->id)->where('language_source_id',$current_language->id)->first();
                    if(!$translation) {
                        echo "\n Error not found for : ".basename($language,'.json');
                        continue;
                    }
                    $translation->description = $description;
                    $translation->save();
                    echo "\nSuccessfully Saved description for.".basename($language,'.json');
                }
            }
        }
    }
}
