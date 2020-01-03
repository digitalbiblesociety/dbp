<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        ini_set('memory_limit', '2500M');
        $this->call(RealDataSeeder::class);

        echo "Seeding Bible Verses \n";
        $bible_filesets = \App\Models\Bible\BibleFileset::where('set_type_code', 'text_plain')->get();
        foreach ($bible_filesets as $bible_fileset) {
            echo "\n attempting to seed: ". $bible_fileset->id;
            factory(\App\Models\Bible\BibleVerse::class, random_int(1, 10))->create(['hash_id' => $bible_fileset->hash_id]);
        }

        echo "Seeding User Role \n";
        $this->call(UserRoleSeeder::class);

        echo "Seeding User Highlight Color \n";
        $this->call(UserHighlightColorSeeder::class);

        echo "Seeding Projects \n";
        $this->call(ProjectsSeeder::class);

        echo "Seeding Users \n";
        $this->call(UsersSeeder::class);

        echo "Seeding Annotation \n";
        $this->call(AnnotationSeeder::class);
    }
}
