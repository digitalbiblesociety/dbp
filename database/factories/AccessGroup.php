<?php

use Faker\Generator as Faker;
use App\Models\User\AccessGroup;
use App\Models\User\AccessType;
use App\Models\Bible\BibleFileset;

$factory->define(AccessGroup::class, function (Faker $faker) {
    return [
        'name'             => 'Test Generated '.$faker->companySuffix,
        'description'      => $faker->paragraph(random_int(1, 5))
    ];
});

$factory->define(AccessType::class, function (Faker $faker) {
    return [
        'name'         => 'Test Generated '.$faker->companySuffix,
        'country_id'   => null,
        'continent_id' => null,
        'allowed'      => 1,
    ];
});

$factory->state(AccessType::class, 'with_country', function (Faker $faker) {
    return ['country_id' => $faker->countryCode];
});

$factory->state(AccessType::class, 'with_continent', function (Faker $faker) {
    return ['continent_id' => $faker->randomElement(['EU', 'AS', 'NA', 'AF', 'SA', 'OC', 'AN'])];
});

$factory->afterCreating(AccessGroup::class, function ($group) {
    $group->types()->attach(factory(AccessType::class, random_int(1, 5))->create());
    $group->filesets()->attach(factory(BibleFileset::class, random_int(1, 5))->create());
});
