<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
use Symfony\Component\Yaml\Yaml;
use App\Models\Language\LanguageDialect;
use App\Models\Language\LanguageAltName;
use App\Models\Language\LanguageClassification;

use App\Models\Country\CountryLanguage;
class language_seeder extends Seeder
{

    public function run()
    {
        $languages = Yaml::parse(file_get_contents(storage_path().'/data/languages/languages.yaml'));
        foreach($languages as $id => $language) {

            // Skip Entries Not officially in the Glottolog
            if (strpos($language['code+name'], 'NOCODE_') !== false) continue;
            //$iso = preg_match_all("/\[([^\]]*)\]/", $language['code+name'], $isoArray);
            $langoid = new Language();
            $langoid->id = $id;
            $langoid->name = $language['name'] ?? NULL;
            $langoid->iso = $language['iso_639-3'] ?? NULL;
            $langoid->status = $language['language_status'] ?? NULL;
            $langoid->maps = $language['language_maps'] ?? NULL;
            $langoid->development = $language['language_development'] ?? NULL;
            $langoid->use = $language['language_use'] ?? NULL;
            $langoid->location = $language['location'] ?? NULL;
            $langoid->area = $language['macroarea-gl'] ?? NULL;
            $langoid->population = $language['population_numeric'] ?? NULL;
            $langoid->population_notes = $language['population'] ?? NULL;
            $langoid->notes = $language['other_comments'] ?? NULL;
            $langoid->latitude = $language['coordinates']['latitude'] ?? NULL;
            $langoid->longitude = $language['coordinates']['longitude'] ?? NULL;
            $langoid->country_id = $country ?? NULL;
            if(isset($language['typology'])) {
                $langoid->typology = implode(',', $language['typology']);
            }
            if(isset($language['writing'])) {
                $langoid->writing = implode(',', $language['writing']);
            }
            $langoid->save();
        }
        foreach($languages as $id => $language) {

            if (strpos($language['code+name'], 'NOCODE_') !== false) continue;

            if(isset($language['alternate_names'])) {
                foreach($language['alternate_names'] as $alternate_name) {
                    $alternative = new LanguageAltName();
                    $alternative->glotto_id = $id;
                    $alternative->name = $alternate_name;
                    $alternative->save();
                }
            }

            if(isset($language['classification-gl'])) {
                foreach ($language['classification-gl'] as $order => $classification) {
                    preg_match_all("/\[([^\]]*)\]/", $classification, $classCodesArray);
                    $class = new LanguageClassification();
                    $class->order = $order;
                    $class->glotto_id = $id;
                    $class->classification_id = $classCodesArray[1][0];
                    $class->name = $language['classification'][$order] ?? $classification;
                    $class->save();
                }
            }

            if(isset($language['country'])) {

                if(is_array($language['country'])) {
                    foreach($language['country'] as $country) {
                        preg_match_all("/\[([^\]]*)\]/", $country, $countryCodeArray);
                        if(count($countryCodeArray[1]) > 0) {
                            CountryLanguage::insert([
                                'country_id' => $countryCodeArray[1][0],
                                'glotto_id' => $id
                            ]);
                        } else {
                            $country = \App\Models\Country\Country::where('name',$language['country'])->first();
                            if(!$country) continue;
                            CountryLanguage::insert([
                                'country_id' => $country->id,
                                'glotto_id' => $id
                            ]);
                        }
                    }
                } else {
                    preg_match_all("/\[([^\]]*)\]/", $language['country'], $countryCodeArray);
                    if(count($countryCodeArray[1]) > 0) {
                        CountryLanguage::insert([
                            'country_id' => $countryCodeArray[1][0],
                            'glotto_id' => $id
                        ]);
                    } else {
                        $country = \App\Models\Country\Country::where('name',$language['country'])->first();
                        if(!$country) continue;
                        CountryLanguage::insert([
                            'country_id' => $country->id,
                            'glotto_id' => $id
                        ]);
                    }

                }

            }


            if(isset($language['dialects'])) {
                foreach($language['dialects'] as $dialect) {
                    preg_match_all("/\[([^\]]*)\]/", $dialect, $dialectCodesArray);

                    $new_dialect = new LanguageDialect();
                    $new_dialect->glotto_id = $id;
                    $new_dialect->name = $dialect;

                    if(count($dialectCodesArray[1]) > 0) {
                        $dialectLanguage = Language::where('iso',$dialectCodesArray[1][0])->first();
                        if($dialectLanguage) {
                            $new_dialect->dialect_id = $dialectLanguage->id;
                        }
                    }

                    $new_dialect->save();

                }
            }

        }

    }

}