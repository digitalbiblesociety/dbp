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
        ini_set('memory_limit', '2500M');

        $this->call(RealDataSeeder::class);
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

        echo "Seeding Access Key \n";
        $this->call(AccessKeySeeder::class);

    }
}
