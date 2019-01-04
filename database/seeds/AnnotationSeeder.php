<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class AnnotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $highlighted_colors = \DB::connection('dbp_users')->table('user_highlight_colors')->select('id')->get();
        $bibles = \DB::connection('dbp')->table('bibles')->select('id')->get();
        $books  = \DB::connection('dbp')->table('books')->select('id')->get();

        $users = \App\Models\User\User::all();
        foreach ($users as $user) {
            $note_count = random_int(1, 20);
            while ($note_count > 0) {
                $user->notes()->create([
                    'bible_id'    => $bibles->random()->id,
                    'book_id'     => $books->random()->id,
                    'chapter'     => random_int(1, 25),
                    'verse_start' => random_int(1, 40),
                    'notes'       => encrypt($faker->paragraph())
                ]);
                $note_count--;
            }

            $highlight_count = random_int(1, 20);
            while ($highlight_count > 0) {
                $user->highlights()->create([
                    'bible_id'          => $bibles->random()->id,
                    'book_id'           => $books->random()->id,
                    'chapter'           => random_int(1, 25),
                    'verse_start'       => random_int(1, 40),
                    'highlight_start'   => random_int(1, 15),
                    'highlighted_words' => random_int(1, 25),
                    'highlighted_color' => $highlighted_colors->random()->id
                ]);
                $highlight_count--;
            }

            $bookmark_count = random_int(1, 20);
            while ($bookmark_count > 0) {
                $user->bookmarks()->create([
                    'bible_id'          => $bibles->random()->id,
                    'book_id'           => $books->random()->id,
                    'chapter'           => random_int(1, 25),
                    'verse_start'       => random_int(1, 40)
                ]);
                $bookmark_count--;
            }
        }

    }
}
