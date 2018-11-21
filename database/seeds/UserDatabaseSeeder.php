<?php

use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed factories & and their related users
        factory(Project::class, 500)->make()->each(function ($projects) {
            $projects->users()->save(factory(User::class, random_int(100,1000))->make());
        });
        factory(Project::class, 150)->states('sensitive')->make()->each(function ($projects) {
            $projects->users()->save(factory(User::class, random_int(100,1000))->make());
        });

    }
}
