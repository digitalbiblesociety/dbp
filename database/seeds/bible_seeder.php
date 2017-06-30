<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Language\Language;
use App\Models\Language\Alphabet;
use App\Models\Bible\Bible;
class bible_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederhelper = new SeederHelper();
        $bibles = $seederhelper->csv_to_array(storage_path() . "/data/bibles/bibles.csv");
        foreach ($bibles as $key => $bible) {

                if($bible["iso"] == "eng" AND isset($bible["eng_title"])) {
                    $bible_translations[] = array(
                        "iso" => "eng",
                        "vernacular" => 1,
                        "name" => $bible["eng_title"],
                        "abbr" => $bible["abbr"],
                        "description" => $bible["description"]
                    );
                } elseif(isset($bible["eng_title"])) {
                    $bible_translations[] = array(
                        "iso" => "eng",
                        "vernacular" => 0,
                        "name" => $bible["eng_title"],
                        "abbr" => $bible["abbr"],
                        "description" => $bible["description"]
                    );
                }

                if(isset($bible["vern_title"])) {
                    $bible_translations[] = array(
                        "iso" => $bible["iso"],
                        "vernacular" => 1,
                        "name" => $bible["vern_title"],
                        "abbr" => $bible["abbr"],
                        "description" => $bible["description"]
                    );
                }
        }

        $languages = Language::select("iso")->get();
        foreach($languages as $language) {
            $isoCodes[] = $language->iso;
        }

        $scripts = Alphabet::select("script")->get();
        foreach($scripts as $script) {
            $alphabets[] = $script->script;
        }

        foreach($bibles as $bible) {
            unset($bible["vern_title"]);
            unset($bible["eng_title"]);
            unset($bible["description"]);
            unset($bible["vern_lang"]);
            unset($bible["eng_lang"]);
            $bible["scope"] = $bible["size"];
            unset($bible["size"]);
            $bible["copyright"] = $bible["cpy"];
            unset($bible["cpy"]);
            unset($bible["publisher"]);
            unset($bible["transcription"]);
            unset($bible["text"]);
            unset($bible["ref"]);
            unset($bible["transcription"]);
            unset($bible["translator"]);
            unset($bible["user_id"]);
            unset($bible["created_at"]);
            unset($bible["updated_at"]);

            if(!in_array($bible["iso"], $isoCodes)) {
                echo "\n Missing Iso Code:".$bible['abbr'].' | '.$bible["iso"];
                $failures[] = $bible["abbr"];
            } elseif(!in_array($bible["script"],$alphabets)) {
                echo "\n Missing Script Code:".$bible['abbr'].' | '.$bible["script"];
                $failures[] = $bible["abbr"];
            } else {
                $glotto = Language::where('iso',$bible['iso'])->first();
                if(!$glotto) {
                    echo "\n Missing Iso Code: ".$bible['iso'];
                    continue;
                }
                unset($bible['iso']);
                $bible['glotto_id'] = $glotto->id;
                DB::table('bibles')->insert($bible);
            }


        }

        foreach($bible_translations as $translation) {
                if(!in_array($translation["abbr"],$failures)) {

                    $glotto = Language::where('iso',$translation['iso'])->first();
                    if(!$glotto) {
                        echo "\n Missing Iso Code: ".$translation['iso'];
                        continue;
                    }
                    $translation['glotto_id'] = $glotto->id;
                    unset($translation['iso']);
                    DB::table('bible_translations')->insert($translation);
                }
        }

        // Update Sophia Boolean
        $sophiaBibles = \DB::connection('sophia')->table('bible_list')->get();
        foreach ($sophiaBibles as $sophiaBible) {
            $bible = Bible::where('abbr',$sophiaBible->fcbhId)->first();
            if(isset($bible)) {
                $bible->sophia = true;
                $bible->save();
            } else {
                echo "\n !!!--- Unmatched Sophia Bible Abbreviation: ".$sophiaBible->fcbhId. " ---!!! ";
            }
        }
/*
        $videos = $seederhelper->csv_to_array('https://docs.google.com/a/dbs.org/spreadsheets/d/1TpVhkRcgcCt_0lxCItzanni7iyfYGtpcLjH2ns-oqOA/export?format=csv&id=1TpVhkRcgcCt_0lxCItzanni7iyfYGtpcLjH2ns-oqOA');
        foreach($videos as $video) {
            DB::table('bible_videos')->insert($video);
        }
*/
    }

}