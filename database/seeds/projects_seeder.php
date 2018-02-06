<?php

use Illuminate\Database\Seeder;
use App\Models\User\Project;
use App\Models\User\User;
use Faker\Factory as Faker;
class projects_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $faker = Faker::create();
        $project = Project::create([
        	'name'            => 'inScript',
			'url_avatar'      => 'https://inscript.org/logo.svg',
			'url_avatar_icon' => 'https://inscript.org/logo-icon.svg',
			'url_site'        => 'https://inscript.org',
			'description'     => 'A multi pane Bible Reader',
        ]);

	    $i = 0;
        while($i > 50) {
	        $project = Project::create([
		        'name'            => $faker->name,
		        'url_avatar'      => $faker->url,
		        'url_avatar_icon' => $faker->url,
		        'url_site'        => $faker->url,
		        'description'     => $faker->realText(150,2),
	        ]);
	        $users = User::inRandomOrder()->take(random_int(3,10));
	        $project->members->sync($users);
        }

    }
}
