<?php

use Faker\Generator as Faker;
use App\Models\User\User;
use App\Models\User\Profile;
use App\Models\User\Key;
use App\Models\User\AccessGroupKey;

use App\Models\Organization\Organization;

$factory->define(Key::class, function (Faker $faker) {
    return [
        'key'         => $faker->bankAccountNumber,
        'name'        => $faker->title,
        'description' => $faker->paragraph,
    ];
});

$factory->afterCreating(Key::class, function ($key) {
    $key->access()->save(factory(AccessGroupKey::class));
});

$factory->define(User::class, function (Faker $faker) {
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

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'theme_id'         => 1,
        'location'         => $faker->streetAddress,
        'bio'              => $faker->paragraph(2, true),
        'twitter_username' => $faker->userName,
        'github_username'  => $faker->userName,
    ];
});

$factory->state(User::class, 'developer', function ($faker) {
    return [];
});
$factory->afterCreatingState(User::class, 'developer', function ($user, $faker) {
    $user->keys()->save(factory(\App\Models\User\Key::class)->make());
});


$factory->define(Organization::class, function ($faker) {
    return [
        'slug'           => $faker->slug,
        'abbreviation'   => $faker->lexify('???'),
        'notes'          => $faker->paragraph,
        'primaryColor'   => $faker->hexcolor,
        'secondaryColor' => $faker->hexcolor,
        'inactive'       => $faker->boolean,
        'url_facebook'   => $faker->url,
        'url_website'    => $faker->url,
        'url_donate'     => $faker->url,
        'url_twitter'    => $faker->url,
        'address'        => $faker->streetName,
        'address2'       => '',
        'city'           => $faker->city,
        'state'          => $faker->stateAbbr,
        'zip'            => $faker->postcode,
        'phone'          => $faker->phoneNumber,
        'email'          => $faker->email,
        'email_director' => $faker->email,
        'latitude'       => $faker->latitude,
        'longitude'      => $faker->longitude,
   ];
});


$factory->define(\App\Models\User\Role::class, function ($faker) {
    return [
        'name'        => $faker->name,
        'slug'        => str_slug($faker->name),
        'description' => $faker->name
    ];
});