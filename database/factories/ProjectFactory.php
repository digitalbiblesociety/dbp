<?php

use Faker\Generator as Faker;

use App\Models\User\User;
use App\Models\User\Project;
use App\Models\User\ProjectOauthProvider;

// Project Factory
$factory->define(Project::class, function (Faker $faker) {
    return [
        'id'               => random_int(0, 9999),
        'name'             => $faker->name,
        'url_avatar'       => $faker->url,
        'url_avatar_icon'  => $faker->url,
        'url_site'         => $faker->url,
        'description'      => $faker->paragraph(3, true),
        'sensitive'        => false,
        'deleted_at'       => null
    ];
});

// Oauth Provider Factory
$factory->define(ProjectOauthProvider::class, function (Faker $faker) {
    return [
        'name'             => collect(['facebook','google','twitter','github'])->random(),
        'client_id'        => '',
        'client_secret'    => '',
        'callback_url'     => $faker->url,
        'redirect_url'     => $faker->url,
        'description'      => $faker->paragraph(2, true),
        'id'               => factory(User::class)->create()->id
    ];
});

$factory->state(Project::class, 'sensitive', ['sensitive' => true ]);

//$factory->afterCreating(Project::class, function ($projects) {
//    $projects->oauthProviders()->save(factory(ProjectOauthProvider::class)->make());
//    //$projects->oauthProviders()->save(factory(ProjectOauthProvider::class)->make());
//});
