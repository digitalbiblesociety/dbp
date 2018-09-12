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
	    $connection = 'dbp_users';
	    DB::connection($connection)->disableQueryLog();
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
		    'number_values',
		    'access_group_filesets',
			'access_group_types',
			'access_groups',
			'access_type_translations',
			'access_types'
	    ];

    	$tables = [
		    'users',
		    'user_highlights',
		    'user_accounts',
		    'user_roles',
		    'user_keys',
		    'user_notes',
		    'access_group_keys',
		    'user_note_tags',
		    'projects',
		    'project_members',
		    'project_oauth_providers',
			'password_resets',
	    ];

    	\Schema::connection($connection)->disableForeignKeyConstraints();
		foreach ($tables as $table) {

			$seedData = $seederHelper->csv_to_array(storage_path('data/csv_exports/'.$table.'.csv'));
			if($table == 'number_values') $table = 'numeral_system_glyphs';
			if($table == 'user_roles') $table = 'role_user';
			\DB::connection($connection)->table($table)->truncate();
			foreach($seedData as $data) {

				// Removed random_order field from Books
				if($table == 'books') {
					unset($data['random_order']);
				}

				if($table == 'numeral_system_glyphs') {
					$data['numeral_system_id'] = $data['number_id'];
					$data['numeral_written'] = '';
					unset($data['number_id']);
				}

				$data = $this->importUsers($table,$data);

				foreach($data as $key => $item) {
					if($item == '0000-00-00 00:00:00') $data[$key] = \Carbon\Carbon::now()->toDateTimeString();
					if($item == 'NULL') $data[$key] = null;
				}
				\DB::connection($connection)->table($table)->insert($data);
			}

		}
	    \Schema::connection($connection)->enableForeignKeyConstraints();

    }

    private function importUsers($table,$data)
    {

	    if($table == 'users') {

		    $data['token'] = $data['email_token'];
		    $data['activated'] = 1;
		    $data['notes'] = $data['id'];
		    $data['name'] = $data['name'] ?? $data['email'];
		    $data['password'] = "needs_resetting";
		    $data['token'] = unique_random('users','token',16);

		    unset($data['nickname']);
		    unset($data['avatar']);
		    unset($data['id']);
		    unset($data['email_token']);
		    unset($data['verified']);
		    return $data;
	    }

	    $simple_replacements = ['user_highlights','user_accounts','user_keys','user_notes',];
	    if(in_array($table,$simple_replacements)) {
		    $user = \App\Models\User::where('notes',$data['user_id'])->first();
		    $data['user_id'] = $user->id;
		    return $data;
	    }

	    if($table == 'project_members') {
		    $user = \App\Models\User::where('notes',$data['user_id'])->first();
		    $data['user_id'] = $user->id;
		    $data['subscribed'] = 0;
		    return $data;
	    }

	    if($table == 'password_resets') {
		    unset($data['updated_at']);
		    return $data;
	    }

		return $data;
    }

}
