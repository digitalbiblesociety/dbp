<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    ini_set('memory_limit', '10024M');

	    $this->call(countries_seeder::class);

	    // 1. Languages
	    $this->call(language_seeder::class);
	    $this->call(language_equivalents_glottolog::class);
	    $this->call(language_equivalents_iso::class);
	    $this->call(language_primaryCountrySeeder::class);
	    $this->call(language_translations::class);
	    $this->call(language_descriptions::class);
	    $this->call(language_joshuaProject_seeder::class);

	    // Languages - Alphabets
	    $this->call(alphabet_seeder::class);
	    $this->call(language_alphabets::class);

	    // 2. Countries

	    $this->call(countries_translations_seeder::class);
	    $this->call(countries_regions_seeder::class);
	    $this->call(countries_factbook_seeder::class);
	    $this->call(countries_language_seeder::class);

		// 3. Bibles
	    $this->call(bible_seeder::class);
	    $this->call(bible_links_seeder::class);

	    // 4. Organizations
	    $this->call(organizations_seeder::class);
	    $this->call(users_seeder::class);

	        // 4.1 Organization Equivalents
	        $this->call(bible_equivalents_bibleGateway::class);
	        $this->call(bible_equivalents_bibleSearch::class);
	        $this->call(bible_equivalents_inScript::class);
	        $this->call(bible_equivalents_eBible::class);
	        $this->call(bible_equivalents_eSword::class);
	        $this->call(bible_equivalents_dbl::class);
	        $this->call(bible_equivalents_dbp::class);
	        $this->call(bible_equivalents_gbc::class);

	    $this->call(bible_books_seeder::class);
	    $this->call(bible_audio_seeder::class);
	    $this->call(bible_file_timestamps_seeder::class);
	    $this->call(bible_filesets_seeder::class);
	    $this->call(bible_translators_seeder::class);

    }
}
