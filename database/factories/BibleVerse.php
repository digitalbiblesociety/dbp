<?php

use Faker\Generator as Faker;
use App\Models\Bible\Book;

$factory->define(\App\Models\Bible\BibleVerse::class, function (Faker $faker) {
    return [
        'hash_id'     => \App\Models\Bible\BibleFileset::where('set_type_code', 'text_plain')->inRandomOrder()->first()->hash_id,
        'book_id'     => Book::inRandomOrder()->first()->id,
        'chapter'     => random_int(1, 150),
        'verse_start' => random_int(1, 176),
        'verse_text'  => $faker->paragraph
    ];
});
