<?php

namespace App\Console\Commands\BibleFormats;

use App\Models\Bible\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class FormatRunberg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'format:runberg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $chapters = glob(storage_path('data/bibles/runeberg/*.html'));
        foreach ($chapters as $chapter_path) {
            $book_number = substr($chapter_path, 40, 2);
            $chaptersByBook[$book_number][] = $chapter_path;
        }

        foreach ($chaptersByBook as $book_number => $chapters) {
            $chapters_in_order = Arr::sort($chapters);
            $book =  Book::where('protestant_order', (int) $book_number)->first();
            if (!$book) {
                echo "skipping: $book_number";
                continue;
            }
            $output_path = storage_path('data/bibles/runeberg/usfm/');
            $book_path = $output_path . '/' . str_pad($book->protestant_order, 2, '0', STR_PAD_LEFT) . '_' . $book->id . '.SFM';
            $first_chapter = true;
            foreach ($chapters_in_order as $key => $chapter_path) {
                $chapter_text = file_get_contents($chapter_path);
                preg_match_all('/<pre>\s+(.*) \(.*\), (\d+) Kapitlet\s+(.*)\s+/', $chapter_text, $chapter_title);

                if ($first_chapter) {
                    $this->populateFrontMatter($book, $chapter_title, $book_path);
                    $first_chapter = false;
                }

                $chapter  = "\c ".$chapter_title[2][0] . "\n";
                $chapter .= "\s1 ".$chapter_title[3][0] . "\n";
                $chapter .= "\p" . "\n";
            }
        }
    }

    private function populateFrontMatter($book, $chapter_title, $book_path)
    {
        $book_name = $chapter_title[1][0] ?? '['.$book->name.']';

        $front_matter = '\id '.$book->id."\n";
        $front_matter .= "\ide UTF-8\n";
        $front_matter .= "\sts 1\n";
        $front_matter .= "\h ".$book_name."\n\n";

        file_put_contents($book_path, $front_matter);
    }

    // Step 1)
        // Replace __ \n ? ?(\d+)\. __ with \n\v \1.
}
