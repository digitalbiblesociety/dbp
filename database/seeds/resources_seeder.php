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
	    \DB::table('resource_links')->delete();
	    \DB::table('resource_translations')->delete();
	    \DB::table('resources')->delete();

	    echo "\nBeginning G.R.N";
	    $this->seed_grn();

	    echo "\nBeginning Jesus Film";
	    $this->seed_jesusFilm();

	    echo "\nBeginning Home for Bible Translators";
	    $this->seed_homeForBibleTranslators();


	    echo "\nBeginning Digital Bible Society";
	    $this->seed_digitalBibleSociety();
    }

    public function seed_digitalBibleSociety()
    {
    	$organization = Organization::where('slug','digital-bible-society')->first();

    	// Handle Libraries as Collections
		$libraries = json_decode(file_get_contents(storage_path('data/resources/treasure_libraries/libraries.json')));
		foreach ($libraries as $iso => $library) {
			$language = \App\Models\Language\Language::where('iso',$iso)->first();
			$resource = Resource::create([
				'source_id'        => '',
				'organization_id'  => $organization->id,
				'iso'              => $iso,
				'language_id'      => $language->id,
				'type'             => "Library",
				'cover'            => "https://images.bible.cloud/treasures/box/" . $library->url . "-treasures.jpg",
				'date'             => ""
			]);
			$resource->translations()->create(['iso'=>'eng','language_id' => \App\Models\Language\Language::where('iso','eng')->first()->id,'title' => $library->etitle,'description' => isset($library->description) ? $library->description : "",'tag' => 0,'vernacular' => 0]);
			$resource->translations()->create(['iso'=>$iso,'language_id' => $language->id,'title' => $library->vtitle,'description' => '','tag' => 0,'vernacular' => 1]);
			$resource->links()->create(['url' => "https://dbs.org/libraries/".$library->url."-treasures",'type' => "Library",'title' => "Collection Preview & Download" ]);
		}
    }

    public function seed_homeForBibleTranslators()
    {
    	$books = json_decode(file_get_contents(storage_path('data/resources/hbft.json')),true);
    	foreach ($books as $book) {

    		if(strlen($book['title']) > 191) {
    			if(strpos($book['title'], ':') !== false) {
    				$split = explode(':',$book['title']);
    				$book['title'] = $split[0];
				    $book['description'] = $split[1];
			    }
		    }

    		if(!isset($book['iso'])) { dd($book); }
    		$language = \App\Models\Language\Language::where('iso',$book['iso'])->select(['iso','id'])->first();
		    $resource = Resource::create([
			    'source_id'        => '',
			    'organization_id'  => "23",
			    'language_id'      => $language->id,
			    'iso'              => $book['iso'],
			    'type'             => "Book",
			    'cover'            => ($book['cover']) ? "https://bible.cloud/images/resources/".$book['cover'] : '',
			    'date'             => $book['date']
		    ]);

		    $resource->translations()->create([
		    	'title'       => $book['title'],
			    'description' => $book['description'],
			    'language_id' => $language->id,
			    'iso'         => $book['iso'],
			    'tag'         => 0,
			    'vernacular'  => ($book['iso'] == "eng") ? 1 : 0
		    ],
			[
				'title'       => $book['author'],
				'description' => "Author",
				'tag'         => 1,
				'vernacular'  => ($book['iso'] == "eng") ? 1 : 0
			]);

		    $resource->links()->create([
			    'url'   => $book['link'],
			    'type'  => "Print",
			    'title' => "Store"
		    ]);
	    }

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
	    $languages = json_decode(file_get_contents(storage_path('data/resources/arclight/arclight-languages.json')), true);
		$media_components = json_decode(file_get_contents(storage_path('data/resources/arclight/JFM-media-components.json')), true);
		$media_components = collect($media_components['_embedded']['mediaComponents']);

	    $paths = glob(storage_path('/data/resources/arclight/JFM-media-compon*.json'));
	    foreach($paths as $path) {
	    	$collections = json_decode(file_get_contents($path), true);
	    	foreach ($collections['_embedded'] as $collection) {
	    		foreach($collection as $film) {
	    			$current_film = $media_components->where('mediaComponentId',$film['mediaComponentId'])->first();
				    if(!isset($film['languageId'])) { continue; }
	    			if(!isset($languages[$film['languageId']])) { continue; }

				    $language = \App\Models\Language\Language::where('iso',$languages[$film['languageId']])->select(['iso','id'])->first();
					if(!$language) {continue;}
	    			$resource = Resource::create([
	    				'source_id'        => $film['mediaComponentId'],
					    'organization_id'  => "24",
					    'language_id'      => $language->id,
					    'iso'              => $languages[$film['languageId']],
					    'type'             => "Film",
					    'cover'            => "https://bible.cloud/images/resources/".$film['mediaComponentId'].'.png',
					    'cover_thumbnail'  => "https://bible.cloud/images/resources/".$film['mediaComponentId'].'_thumbnail.png'
				    ]);
				    $language = \App\Models\Language\Language::where('iso','eng')->select(['iso','id'])->first();
					if($current_film) $resource->translations()->create(['language_id' => $language->id,'title' => $current_film['title'],'description' => $current_film['shortDescription'],'iso' => 'eng','vernacular' => 0,'tag' => 0]);

					if(isset($film['downloadUrls']['low']['url'])) {
						$resource->links()->create([
							'url'   => $film['downloadUrls']['low']['url'],
							'size'  => $this->formatBytes($film['downloadUrls']['low']['sizeInBytes']),
							'type'  => "Low Quality",
							'title' => "Low Quality Download"
						]);
					}
				    if(isset($film['downloadUrls']['high']['url'])) {
					    $resource->links()->create([
							'url'   => $film['downloadUrls']['high']['url'],
							'size'  => $this->formatBytes( $film['downloadUrls']['high']['sizeInBytes'] ),
							'type'  => "High Quality",
							'title' => "High Quality Download"
						]);
				    }
			    }
		    }
	    }


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
    			$links = collect($language->programs_info[$key]->links);

    			$spoken_language = \App\Models\Language\Language::where('iso',$iso)->first();
    			if(!$language) {
    				echo "\n Missing: iso ". $iso;
    				continue;
    			}
    			$currentResource = Resource::create([
    				'organization_id' => $organization_id,
				    'language_id'     => $spoken_language->id,
    				'iso'             => $iso,
				    'source_id'       => $recording->program_num,
					'cover'           => $recording->thumbnail,
					'cover_thumbnail' => NULL,
					'date'            => NULL,
					'type'            => 'audio',
			    ]);

    			ResourceTranslation::create([
					'iso'         => 'eng',
					'language_id' => $spoken_language->id,
					'resource_id' => $currentResource->id,
					'vernacular'  => ($iso == 'eng') ? true : false,
					'tag'         => false,
					'title'       => $recording->title,
					'description' => NULL,
			    ]);

    			foreach ($language->programs_info[$key]->links as $link) {

    				if(isset($link->resources->audio->low[0])) {
    					ResourceLink::create(['resource_id' => $currentResource->id,'title' => 'Compressed mp3','type' => 'mp3','url' => $link->resources->audio->low[0]]);
				    }

				    if(isset($link->resources->audio->mp3[0])) {
					    ResourceLink::create( [
						    'resource_id' => $currentResource->id,
						    'title'       => 'Uncompressed mp3',
						    'type'        => 'mp3',
						    'url'         => $link->resources->audio->mp3[0],
					    ] );
				    }
			    }

		    }

	    }

    }

	function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . ' ' . $units[$pow];
	}

}
