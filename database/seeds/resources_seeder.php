<?php

use Illuminate\Database\Seeder;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourceLink;
use App\Models\Resource\ResourceTranslation;
use App\Models\Organization\Organization;
class resources_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	dd($this->seed_jesusFilm());
	    //\DB::table('resource_links')->delete();
	    //\DB::table('resource_translations')->delete();
	    //\DB::table('resources')->delete();

    	// Run GRN Seeder
        //$this->seed_grn();
    }

    public function seed_jesusFilm()
    {
	    if(!file_exists(storage_path('/data/resources/arcLight.json'))) {
		    $current_page = 1;
		    $languages = file_get_contents('https://api.arclight.org/v2/media-languages?_format=json&term=Sign&page=1&limit=10&apiKey=5266a820dfd249.67873545');
	    	while($languages["page"] > $languages["pages"]) {

			    $media_languages[] = collect($languages["_embedded"]["mediaLanguages"])->pluck('languageId','iso3');
			    $current_page++;
		    }

	    	file_put_contents(storage_path('/data/resources/arclight_languages.json'),$languages);
	    	$languages = json_decode($languages);
	    } else {
		    $languages = json_decode(file_get_contents(storage_path('/data/resources/arcLight.json')), true);
	    }

	    dd($languages);



    }


    public function seed_grn()
    {
		$organization_id = Organization::where('slug','global-recordings-network')->first()->id;
	    $language_files = glob(storage_path('/data/resources/grn/*.json'));
    	foreach ($language_files as $language_file) {
		    $language = json_decode(file_get_contents($language_file));
		    $language = $language[0];
		    $iso = $language->iso;

    		foreach ($language->recordings as $key => $recording) {
    			$currentResource = Resource::create([
    				'organization_id' => $organization_id,
    				'iso'             => $iso,
				    'source_id'       => $recording->program_num,
					'cover'           => $recording->thumbnail,
					'cover_thumbnail' => NULL,
					'date'            => NULL,
					'type'            => 'audio',
			    ]);

    			ResourceTranslation::create([
					'iso'         => 'eng',
					'resource_id' => $currentResource->id,
					'vernacular'  => ($iso == 'eng') ? true : false,
					'tag'         => false,
					'title'       => $recording->title,
					'description' => NULL,
			    ]);

    			foreach ($language->programs_info[$key]->links as $link) {

    				if(isset($link->resources->low[0])) {
    					ResourceLink::create(['resource_id' => $currentResource->id,'title' => 'Compressed mp3','type' => 'mp3','url' => $link->resources->low[0]]);
				    }

				    if(isset($link->resources->mp3[0])) {
					    ResourceLink::create( [
						    'resource_id' => $currentResource->id,
						    'title'       => 'Uncompressed mp3',
						    'type'        => 'mp3',
						    'url'         => $link->resources->mp3[0],
					    ] );
				    }

				    if(isset($link->resources->script['0'])) {
					    ResourceLink::create( [
						    'resource_id' => $currentResource->id,
						    'title'       => 'Script',
						    'type'        => 'website',
						    'url'         => "http://globalrecordings.net/" . $link->resources->script[0],
					    ] );
				    }
			    }

		    }

	    }

    }
}
