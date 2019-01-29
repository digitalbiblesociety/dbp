<?php

namespace App\Console\Commands\BibleFormats;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\Book;
use App\Models\Language\Language;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class FormatGetBible extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'format:getBible {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetcher and Converter for the GetBible Format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $translations = collect(json_decode(file_get_contents('https://raw.githubusercontent.com/getbible/Bibles/master/translations.json')))->flatten();
        switch($this->argument('action')) {
            case 'fetch':
                $this->fetchBibles($translations);
                break;
            case 'convert':
                $this->convertBibles($translations);
                break;
        }
    }

    private function fetchBibles($translations)
    {
        foreach ($translations as $translation) {
            $this->downloadFile($translation->filename, 'https://raw.githubusercontent.com/getbible/Bibles/master/');
        }
    }

    private function downloadFile($current_file, $path)
    {
        $tmpFile = storage_path('data/bibles/getbible/'.$current_file.'.txt');
        $client = new Client(array(
            'base_uri'     => '',
            'verify'       => false,
            'sink'         => $tmpFile,
            'curl.options' => [
                'CURLOPT_RETURNTRANSFER' => true,
                'CURLOPT_FILE'           => $tmpFile
            ]
        ));
        $res = $client->get($path.$current_file.'.txt');
        echo $res->getStatusCode() . "\n";
        echo $res->getHeaderLine('content-type') . "\n";
    }

    private function convertBibles($translations)
    {
        $bibles = glob(storage_path('data/bibles/getbible/*.txt'));
        foreach ($bibles as $bible) {
            $current_translation = $translations->where('filename',basename($bible,'.txt'))->flatten()->toArray();
            $language = Language::where('name',$current_translation[0]->language)->first();

            $this->parseVerses($bible, $language);
        }
    }

    private function parseVerses($bible_file_path, $language)
    {
        $output_path = storage_path('data/bibles/getbible/usfm/'.basename($bible_file_path,'.txt'));
        if(!file_exists($output_path)) {
            mkdir($output_path);
        }

        $raw_verses = file_get_contents($bible_file_path);
        $verses = explode("\n", $raw_verses);
        $previous_book = 'not-yet-set';
        foreach ($verses as $verse) {
            if($verse === '') {
                continue;
            }
            $verse = explode('||',trim($verse));
            $book_number = (int) substr($verse[0],0,2);
            if($book_number > 39) {
                ++$book_number;
            }
            $book = Book::where('protestant_order',$book_number)->orWhere('name',$verse[0])->first();
            if(!$book) {
                echo "\nSkipping $verse[0] in $bible_file_path";
                continue;
            }

            $book_path = $output_path . '/' . str_pad($book->protestant_order, 2, '0', STR_PAD_LEFT) . '_' . $book->id . '.SFM';

            if(((int) $verse[2] === 1) && ((int) $verse[1] === 1)) {
                $this->populateFrontMatter($book, $book_path, $language);
            }

            $verse_prepends = ((int) $verse[2] === 1) ? "\n".'\c '.$verse[1]."\n".'\p'."\n" : '';
            $verse_text = $verse_prepends.'\v '.$verse[2].' '.$verse[3]."\n";

            file_put_contents($book_path,$verse_text, FILE_APPEND);
        }
    }

    private function populateFrontMatter($book, $book_path, $language)
    {
        $bibles = optional(Bible::where('language_id',optional($language)->id)->select('id')->get())->pluck('id')->toArray();
        if($bibles) {
            $book_translation = BibleBook::whereIn('bible_id',$bibles)->where('book_id',$book->id)->first();
        }

        $front_matter = '\id '.$book->id."\n";
        $front_matter .= "\ide UTF-8\n";
        $front_matter .= "\sts 1\n";
        $front_matter .= "\h ".($book_translation->name ?? '['.$book->name.']')."\n\n";

        file_put_contents($book_path,$front_matter);
    }


}
