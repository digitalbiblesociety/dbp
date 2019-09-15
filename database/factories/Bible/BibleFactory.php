<?php

use Faker\Generator as Faker;
use App\Models\Language\Language;
use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Models\Language\NumeralSystem;
use App\Models\Language\Alphabet;

$factory->define(Bible::class, function (Faker $faker) {
    return [
        'id'                => strtoupper($faker->languageCode.$faker->lexify('???')),
        'language_id'       => factory(Language::class)->create()->id,
        'versification'     => factory(Book::class)->create()->versification,
        'numeral_system_id' => factory(NumeralSystem::class)->create()->id,
        'date'              => $faker->year(),
        'scope'             => '',
        'script'            => factory(Alphabet::class)->create()->id,
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

$factory->define(\App\Models\Bible\BibleFilesetType::class, function (Faker $faker) {
    return [
        'set_type_code' => substr($faker->unique()->slug, 0, 15),
        'name'          => $faker->unique()->name,
    ];
});

$factory->define(\App\Models\Bible\BibleFilesetSize::class, function (Faker $faker) {
    return [
        'set_size_code' => substr($faker->unique()->slug, 0, 8),
        'name'          => $faker->unique()->name,
    ];
});

$factory->define(\App\Models\Bible\BibleFileset::class, function (Faker $faker) {
    return [
        'id'            => strtoupper($faker->languageCode.$faker->lexify('????')),
        'hash_id'       => substr($faker->bankAccountNumber, 0, 11),
        'asset_id'      => factory(\App\Models\Organization\Asset::class)->create()->id,
        'set_type_code' => factory(\App\Models\Bible\BibleFilesetType::class)->create()->id,
        'set_size_code' => factory(\App\Models\Bible\BibleFilesetSize::class)->create()->id,
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
        'verse_end'     => rand(1, 179),
    ];
});

$factory->afterCreating(\App\Models\Bible\Bible::class, function ($bibles) {
    $bibles->translations()->save(factory(\App\Models\Bible\BibleTranslation::class)->state('vernacular')->make());
    $bibles->translations()->save(factory(\App\Models\Bible\BibleTranslation::class, rand(1, 3))->make());

    $bibles->links()->save(factory(\App\Models\Bible\BibleLink::class, rand(1, 5))->make());
});



/*
$factory->define(\App\Models\Bible\BibleBook::class, function (Faker $faker) {
    return [
        //
    ];
});
*/
