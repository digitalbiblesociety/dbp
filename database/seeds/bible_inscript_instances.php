<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\inScript\inScriptLanguage;
use App\Models\Language\Language;
use App\Models\Language\LanguageCode;

class bible_inscript_instances extends Seeder
{

	private function prefixKey($prefix, $array)
	{
		$result = [];
		foreach ($array as $key => $value)
		{
			if (is_array($value))
				$result = array_merge($result, $this->prefixKey($key . '_', $value));
			else
				$result[$prefix . $key] = $value;
		}
		return $result;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = json_decode(file_get_contents(storage_path().'/data/bibles/inscript/languages.json'),true);
        foreach($languages as $language) {
        	$output[$language['translation']['iso']] = $this->prefixKey('', $languages);
        }


	    foreach($output as $iso => $translations) {

		    $length = strlen($iso);
		    switch($length) {
			    case 2:
				    $languageCode = LanguageCode::where('source','Iso 639-2')->where('code',$iso)->first();
				    if($languageCode) $language = $languageCode->language;
				    break;
			    case 3:
				    $language = Language::where('iso',$iso)->first();
				    break;
			    case 8:
				    $language = Language::find($iso);
				    break;
			    default:
				    $language = Language::where('name',$iso)->first();
		    }
		    if(!isset($language)) {
		    	echo "\ninScript Languages Error:".$iso;
		    	continue;
		    }
		    foreach($translations as $key => $translation) {
		    	if(!inScriptLanguage::where('key',$language->iso.'_'.$key)->first()) {
				    inScriptLanguage::create([
					    'key' => $language->iso.'_'.$key,
					    'glotto_id' => $language->id,
					    'name' => $translation
				    ]);
			    }
		    }

	    }
    }


}
