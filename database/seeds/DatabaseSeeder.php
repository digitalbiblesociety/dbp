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

	    // Languages - Alphabets
	    $this->call(alphabet_seeder::class);
	    $this->call(language_alphabets::class);

	    // 2. Countries

	    $this->call(countries_translations_seeder::class);
	    $this->call(countries_regions_seeder::class);

		// 3. Bibles
	    $this->call(bible_seeder::class);
	    $this->call(bible_books_seeder::class);

	        // 3.1 Equivalents
	        $this->call(bible_equivalents_dbp::class);

	    // 4. Organizations
	    $this->call(organizations_seeder::class);
	    $this->call(organizations_relationships_dbl::class);

    }
}
