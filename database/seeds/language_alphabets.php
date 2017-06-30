<?php

use Illuminate\Database\Seeder;
use \App\Models\Language\Language;
use App\Models\Language\Alphabet;
use App\Models\Language\AlphabetFont;
class language_alphabets extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scripts = json_decode(file_get_contents(storage_path().'/data/languages/fonts/language_fonts.json'));
        foreach($scripts as $type => $languages) {
            $languages = explode(',',$languages);
            foreach($languages as $languageId) {
                $id = last(explode('-',$type));
                $language = Language::where('iso',$languageId)->first();
                if($language) {
                    if(Alphabet::where('script','=',ucwords($id))->count() != 0) {
                        DB::connection('geo_data')->table('alphabet_language')->insert(['script' => ucwords($id), 'glotto_id' => $language->id]);
                    } else {
                        echo "\nBroken Alphabet Code: ".ucwords($id);
                    }
                } else {
                    echo "\nBroken Iso Code:". $languageId;
                }

            }
        }

        $fonts = json_decode(file_get_contents(storage_path().'/data/languages/fonts/fonts.json'));
        foreach($fonts as $type => $fonts) {
            $fonts = explode(",",$fonts);
            if(is_array($fonts)) {
                foreach($fonts as $font) {
                    $thickness = last(explode('-',$font));
                    switch($thickness) {
                        case "Thin": $thickness = 250;
                        case "Light": $thickness = 300;
                        case "DemiLight": $thickness = 350;
                        case "Regular": $thickness = 400;
                        case "Medium": $thickness = 500;
                        case "Bold": $thickness = 700;
                        case "Black": $thickness = 800;
                        default: $thickness = "";
                    }
                    AlphabetFont::insert(['script_id' => ucwords($type), 'fontName' => $font, 'fontFileName' => $font, 'italic' => 0, 'fontWeight' => $thickness]);
                }
            } else {
                $thickness = last(explode('-',$fonts));
                switch($thickness) {
                    case "Thin": $thickness = 250;
                    case "Light": $thickness = 300;
                    case "DemiLight": $thickness = 350;
                    case "Regular": $thickness = 400;
                    case "Medium": $thickness = 500;
                    case "Bold": $thickness = 700;
                    case "Black": $thickness = 800;
                    default: $thickness = "";
                }
                AlphabetFont::insert(['script_id' => $type, 'fontName' => $fonts, 'fontFileName' => $fonts, 'italic' => 0, 'fontWeight' => $thickness]);
            }

        }

    }
}
