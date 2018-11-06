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
                    if(Alphabet::where('script','=',ucwords($id))->count() !== 0) {
                        DB::connection('dbp')->table('alphabet_language')->insert(['script_id' => ucwords($id), 'language_id' => $language->id]);
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
            $fonts = explode(',',$fonts);
            if(is_array($fonts)) {
                foreach($fonts as $font) {
                    $thickness = last(explode('-',$font));
                    switch($thickness) {
                        case 'Thin': {$thickness = 250;break;}
                        case 'Light': {$thickness = 300;break;}
                        case 'DemiLight': {$thickness = 350;break;}
                        case 'Regular': {$thickness = 400;break;}
                        case 'Medium': {$thickness = 500;break;}
                        case 'Bold': {$thickness = 700;break;}
                        case 'Black': {$thickness = 800;break;}
                        default: $thickness = null;
                    }
                    AlphabetFont::insert(['script_id' => ucwords($type), 'font_name' => $font, 'font_filename' => $font, 'italic' => 0, 'font_weight' => $thickness]);
                }
            } else {
                $thickness = last(explode('-',$fonts));
                switch($thickness) {
                    case 'Thin': {$thickness = 250;break;}
                    case 'Light': {$thickness = 300;break;}
                    case 'DemiLight': {$thickness = 350;break;}
                    case 'Regular': {$thickness = 400;break;}
                    case 'Medium': {$thickness = 500;break;}
                    case 'Bold': {$thickness = 700;break;}
                    case 'Black': {$thickness = 800;break;}
                    default: $thickness = '';
                }
                AlphabetFont::insert(['script_id' => $type, 'font_name' => $fonts, 'font_filename' => $fonts, 'italic' => 0, 'font_weight' => $thickness]);
            }

        }

    }
}
