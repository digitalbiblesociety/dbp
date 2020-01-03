<?php

namespace App\Console\Commands\BibleEquivalents;

use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class SyncBebliaBible extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:beblia {--language=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls and parses Bibles from Beblia.com into usfm';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $root_path = 'http://www.beblia.com/pages/';
        // MainContent_chapterForwardButton

        $chapter_number = 1;
        $book_number = 1;
        $name = $this->option('language');

        $html_string = file_get_contents($root_path."main.aspx?Language=$name&Book=$book_number&Chapter=$chapter_number");
        $html = HtmlDomParser::str_get_html($html_string);
        $books = $html->find('.dropDownListBooks option');
        $totalBooks = count($books);
        $this->info('Books to process: '. $totalBooks);

        if (!file_exists(storage_path('bibles/'.$name))) {
            mkdir(storage_path('bibles/'.$name));
        }

        while ($book_number <= $totalBooks) {
            $this->info('Processing Book'. $book_number);

            $html_string = file_get_contents($root_path."main.aspx?Language=$name&Book=$book_number&Chapter=1");
            $html = HtmlDomParser::str_get_html($html_string);
            $totalChapters = $html->find('.dropDownListBookChapters option');
            $totalChaptersCount = count($totalChapters);
            $this->info('Total Chapters to process'. $totalChaptersCount);
            $chapter_number = 1;

            while ($chapter_number <= $totalChaptersCount) {
                if (file_exists(storage_path('bibles/'.$name.'/'.$book_number.'_'.$chapter_number.'.usfm'))) {
                    $this->info('Chapter '.$chapter_number.' already exists... skipping');
                    $chapter_number++;
                    continue;
                } else {
                    // Keep from hammering beblia
                    sleep(6);
                }
                $this->info('Fetching Chapter'. $chapter_number);
                $html_string = file_get_contents($root_path."main.aspx?Language=$name&Book=$book_number&Chapter=$chapter_number");
                $html = HtmlDomParser::str_get_html($html_string);
                $rows = $html->find('#MainContent_verseText tbody tr td');

                // Generate Chapter Code
                $chapter = '\c '.$totalChapters[$chapter_number - 1]->innertext()."\n\p\n\n";
                foreach ($rows as $i => $row) {
                    if ($row->find('.verseTextButton')) {
                        $chapter .= '\v '.$row->find('.verseTextButton')[0]->innertext().' ';
                    }
                    if ($row->find('.verseTextText')) {
                        $chapter .= $row->find('.verseTextText')[0]->innertext()." \n";
                    }
                }

                $this->info('Saving Chapter'. $chapter_number);
                file_put_contents(storage_path('bibles/'.$name.'/'.$book_number.'_'.$chapter_number.'.usfm'), $chapter);

                $chapter_number++;
            }
            $book_number++;
        }

        $book_abbreviations = ['GEN','EXO','LEV','NUM','DEU','JOS','JDG','RUT','1SA','2SA','1KI','2KI','1CH','2CH','EZR','NEH','EST','JOB','PSA','PRO','ECC','SNG','ISA','JER','LAM','EZK','DAN','HOS','JOL','AMO','OBA','JON','MIC','NAM','HAB','ZEP','HAG','ZEC','MAL','MAT','MRK','LUK','JHN','ACT','ROM','1CO','2CO','GAL','EPH','PHP','COL','1TH','2TH','1TI','2TI','TIT','PHM','HEB','JAS','1PE','2PE','1JN','2JN','3JN','JUD','REV'];

        $this->info("globbing: bibles/$name/*");
        $chapters = glob(storage_path("bibles/$name/*"));

        foreach ($chapters as $path) {
            $filename = explode('-', basename($path));
            $current_book_number = (int) $filename[0];
            $this->info($current_book_number);

            $current_book = $book_abbreviations[$current_book_number - 1];
            $this->info('Currently Processing'. $current_book);
            $book_path = storage_path("bibles/$name/merged/$current_book_number-$current_book.usfm");

            if (!file_exists($book_path)) {
                $startingUSFM = "\id $current_book
\ide UTF-8
\h ".$books[$current_book_number - 1]->innertext()."\n\n";
                file_put_contents($book_path, $startingUSFM);
            }

            $chapter = file_get_contents($path);
            file_put_contents($book_path, "\n".$chapter, FILE_APPEND);
        }
    }
}
