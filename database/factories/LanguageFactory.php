<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Organization\Asset::class, function (Faker $faker) {
    return [
       'id'              => $faker->bankAccountNumber,
       'organization_id' => factory(\App\Models\Organization\Organization::class),
       'asset_type'      => $faker->randomElement(['s3','cloudfront','other']),
       'base_name'       => $faker->url
   ];
});

$factory->define(\App\Models\Language\Language::class, function (Faker $faker) {
    return [
        'glotto_id'     => $faker->unique()->bothify('????????'),
        'iso'           => $faker->unique()->lexify('???'),
        'iso2B'         => $faker->lexify('???'),
        'iso2T'         => $faker->lexify('???'),
        'iso1'          => $faker->lexify('??'),
        'name'          => $faker->colorName.' '.$faker->monthName,
        'country_id'    => function () {
            return factory(\App\Models\Country\Country::class)->make()->id;
        },
        'status_id'     => function () {
            return factory(\App\Models\Language\LanguageStatus::class)->make()->id;
        }
    ];
});

$factory->afterCreating(\App\Models\Language\Language::class, function ($language, $faker) {
    $language->translations()->save(factory(\App\Models\Language\LanguageTranslation::class)->make());
});

$factory->define(\App\Models\Language\LanguageStatus::class, function (Faker $faker) {
    return [
        'id'          => $faker->unique()->bothify('??'),
        'title'       => $faker->name,
        'description' => $faker->paragraph
    ];
});

$factory->define(\App\Models\Language\LanguageTranslation::class, function (Faker $faker) {
    return [
        'language_source_id' => function () {
            return factory(\App\Models\Language\Language::class)->make()->id;
        },
        'language_translation_id' => function () {
            return factory(\App\Models\Language\Language::class)->make()->id;
        },
        'name'        => $faker->name,
        'priority'    => (random_int(0, 10) === 9) ? 9 : null
    ];
});

$factory->define(\App\Models\Country\Country::class, function (Faker $faker) {
    return [
        'id'          => $faker->unique()->countryCode,
        'iso_a3'      => $faker->unique()->lexify('???'),
        'name'        => $faker->name,
        'fips'        => $faker->unique()->lexify('??'),
        'wfb'         => $faker->boolean,
        'ethnologue'  => $faker->boolean,
        'continent'   => $faker->randomElement(['EU', 'AS', 'NA', 'AF', 'SA', 'OC', 'AN'])
    ];
});
