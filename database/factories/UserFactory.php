<?php

use Faker\Generator as Faker;
use App\Models\User\User;
use App\Models\User\Profile;

$factory->define(\App\Models\User\Key::class, function (Faker\Generator $faker) {
    return [
        'key'         => $faker->bankAccountNumber,
        'name'        => $faker->title,
        'description' => $faker->paragraph,
    ];
});

$factory->afterCreating(\App\Models\User\Key::class, function ($key) {
    // Keys
    $key->access()->save(factory(\App\Models\User\AccessGroupKey::class));
});

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name'                           => $faker->unique()->userName,
        'first_name'                     => $faker->firstName,
        'last_name'                      => $faker->lastName,
        'email'                          => $faker->unique()->safeEmail,
        'password'                       => $password ?: $password = bcrypt('secret'),
        'token'                          => str_random(64),
        'activated'                      => true,
        'remember_token'                 => str_random(10),
        'signup_ip_address'              => $faker->ipv4,
        'signup_confirmation_ip_address' => $faker->ipv4,
    ];
});

$factory->define(Profile::class, function (Faker\Generator $faker) {
    return [
        'user_id'          => factory(App\Models\User\User::class)->create()->id,
        'theme_id'         => 1,
        'location'         => $faker->streetAddress,
        'bio'              => $faker->paragraph(2, true),
        'twitter_username' => $faker->userName,
        'github_username'  => $faker->userName,
    ];
});

$factory->afterCreating(\App\Models\User\User::class, function ($users) {
    $users->profile()->save(factory(\App\Models\User\User::class)->make());

    // 10 percent chance to be a developer
    if(rand(1,10) === 10) {
        $users->keys()->save(factory(\App\Models\User\Key::class)->make());
    }

});