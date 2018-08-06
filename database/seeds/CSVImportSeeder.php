<?php

use Illuminate\Database\Seeder;

class CSVImportSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    ini_set('memory_limit', '2064M');
	    set_time_limit(-1);
	    DB::connection('dbp')->disableQueryLog();
	    $seederHelper = new \database\seeds\SeederHelper();

    	$tables = [
    		'alphabets',
		    // 'alphabet_fonts',
		    'alphabet_language',
		    // 'alphabet_numeral_systems',
		    'bibles',
		    'books',
		    'bible_books',
		    'bible_equivalents',
		    'bible_filesets',
		    'bible_file_timestamps',
		    'bible_file_titles',
		    'bible_files',
		    'bible_fileset_connections',
		    //'bible_fileset_relations',
		    'bible_fileset_sizes',
		    'bible_fileset_tags',
		    'bible_fileset_types',
		    'bible_links',
		    'bible_organizations',
		    'bible_size_translations',
		    'bible_translations',
		    'bible_translator',
		    'book_translations',
		    'buckets',
		    // 'connection_translations',
		    // 'connections',
		    'countries',
		    'country_communications',
		    'country_economy',
		    'country_energy',
		    'country_geography',
		    'country_government',
		    'country_issues',
		    'country_joshua_project',
		    'country_language',
		    'country_maps',
		    'country_people',
		    'country_people_ethnicities',
		    'country_regions',
		    'country_religions',
		    'country_translations',
		    'country_transportation',
		    // 'failed_jobs',
		    // 'jobs',
		    'languages',
		    'language_bibleInfo',
		    'language_classifications',
		    'language_codes',
		    'language_dialects',
		    'language_translations',
		    'organizations',
		    'organization_logos',
		    'organization_relationships',
		    'organization_translations',
		    //'resource_connections',
		    'resources',
		    'resource_links',
		    'resource_translations',
		    // 'translator_relations',
		    // 'translators',
		    // 'videos',
		    // 'video_sources',
		    // 'video_tags',
		    // 'video_translations'
		    ];
    	\Schema::connection('dbp')->disableForeignKeyConstraints();
		foreach ($tables as $table) {
			\DB::connection('dbp')->table($table)->truncate();
			$seedData = $seederHelper->csv_to_array(storage_path('data/'.$table.'.csv'));
			foreach($seedData as $data) {
				foreach ($data as $key => $item) {
					if($item == 'NULL') $data[$key] = null;
				}
				\DB::connection('dbp')->table($table)->insert($data);
			}

		}
	    \Schema::connection('dbp')->enableForeignKeyConstraints();

    }

}
