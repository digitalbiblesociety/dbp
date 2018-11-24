<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Bible\Bible::class, function (Faker $faker) {
    return [
        'id'                => strtoupper($faker->languageCode.$faker->lexify('???')),
        'language_id'       => factory(App\Models\User\Language::class)->create()->id,
        'versification'     => factory(App\Models\Bible\Book::class)->create()->versification,
        'numeral_system_id' => factory(App\Models\Language\NumeralSystem::class)->create()->id,
        'date'              => $faker->year(),
        'scope'             => '',
        'script'            => factory(App\Models\Language\Alphabet::class)->create()->id,
        'copyright'         => '© '.$faker->company,
        'in_progress'       => $faker->boolean(),
        'priority'          => rand(0, 9),
        'reviewed'          => $faker->boolean(75),
    ];
});

$factory->define(\App\Models\Bible\BibleTranslation::class, function (Faker $faker) {
    return [
        'language_id' => factory(App\Models\User\Language::class)->create()->id,
        'vernacular'  => false,
        'name'        => $faker->title,
        'description' => $faker->paragraph(rand(1, 3))
    ];
});
$factory->state(\App\Models\Bible\BibleTranslation::class, 'vernacular', ['vernacular' => true ]);

$factory->define(\App\Models\Bible\BibleLink::class, function (Faker $faker) {
    return [
        'type'      => $faker->randomElement(['pdf','web','print','cat']),
        'url'       => $faker->url,
        'title'     => $faker->title(),
        'provider'  => $faker->title(),
        'visibile'  => $faker->boolean(80)
    ];
});

$factory->define(\App\Models\Bible\BibleEquivalent::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->define(\App\Models\Bible\BibleFileset::class, function (Faker $faker) {
    $id = strtoupper($faker->languageCode.$faker->lexify('????'));

    return [
        'id'            => strtoupper($faker->languageCode.$faker->lexify('????')),
        'hash_id'       => '',
        'asset_id'      => '',
        'set_type_code' => '',
        'set_size_code' => '',
        'hidden'        => $faker->boolean(10)
    ];
});

$factory->define(\App\Models\Bible\BibleFile::class, function (Faker $faker) {
    return [
        'id'      => '',
        'hash_id' => '',
        'chapter_start' => rand(1, 150),
        'chapter_end'   => rand(1, 150),
        'verse_start'   => rand(1, 179),
        'verse_end'   => rand(1, 179),
    ];
});

$factory->afterCreating(\App\Models\Bible\Bible::class, function ($bibles) {
    $bibles->translations()->save(factory(\App\Models\Bible\BibleTranslation::class)->state('vernacular')->make());
    $bibles->translations()->save(factory(\App\Models\Bible\BibleTranslation::class, rand(1, 5))->make());

    $bibles->links()->save(factory(\App\Models\Bible\BibleLink::class, rand(1, 5))->make());
});


$factory->define(\App\Models\Bible\BibleBook::class, function (Faker $faker) {
    return [
        //
    ];
});
