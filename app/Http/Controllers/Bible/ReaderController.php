<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Http\Controllers\Controller;
use App\Models\Bible\BibleBook;
use App\Models\Bible\Book;
use App\Models\Language\Language;
use App\Models\Bible\Bible;
use App\Models\User\Key;
use App\Traits\AccessControlAPI;

class ReaderController extends APIController
{
    use AccessControlAPI;

    public function languages()
    {
        $languages = \Cache::remember('Bible_is_languages', 2400, function () {

            $project_key = Key::where('name', 'bible.is')->first();
            $access_control = $this->accessControl($project_key->key, 'api');

            $languages = Language::select(['languages.id', 'languages.name', 'autonym.name as autonym'])
                                 ->leftJoin('language_translations as autonym', function ($join) {
                                     $join->on('autonym.language_source_id', 'languages.id');
                                     $join->on('autonym.language_translation_id', 'languages.id');
                                     $join->orderBy('autonym.priority', 'desc');
                                 })
                                 ->whereHas('filesets', function ($query) use ($access_control) {
                                     $query->whereIn('hash_id', $access_control->hashes);
                                     $query->whereHas('fileset', function ($query) {
                                         $query->where('set_type_code', 'text_plain');
                                     });
                                 })->withCount('bibles')->get();
            return $languages;
        });


        return view('bibles.reader.languages', compact('languages'));
    }

    public function bibles($language_id)
    {
        $project_key = Key::where('name', 'bible.is')->first();
        $access_control = $this->accessControl($project_key->key, 'api');

        $bibles = Bible::with('translations')->whereHas('filesets', function ($q) use ($access_control) {
            $q->whereIn('bible_filesets.hash_id', $access_control->hashes)->where('set_type_code', 'text_plain');
        })->where('language_id', $language_id)->get();

        return view('bibles.reader.bibles', compact('bibles'));
    }

    public function books($bible_id)
    {
        $bible = Bible::where('id', $bible_id)->first();
        $language_id = $bible->language_id;
        $sophia_books = \DB::connection('sophia')->table($bible_id.'_vpl')->select('book')->distinct()->get();
        $books = Book::whereIn('id_usfx', $sophia_books->pluck('book')->toArray())->orderBy('protestant_order', 'asc')->get();
        $bible_books = BibleBook::where('bible_id', $bible_id)->whereIn('book_id', $books->pluck('id')->toArray())->get();

        foreach ($books as $book) {
            $currentBook = $bible_books->where('book_id', $book->id)->first();
            $book->vernacular_title = $currentBook ? $currentBook->name : null;
            $book->existing_chapters = $currentBook ? $currentBook->chapters : null;
        }

        return view('bibles.reader.books', compact('books', 'bible_id', 'language_id'));
    }

    public function chapter($bible_id, $book_id, $chapter)
    {
        $verses = \DB::connection('sophia')->table($bible_id.'_vpl')->where('book', $book_id)->where('chapter', $chapter)->orderBy('verse_start')->get();
        return view('bibles.reader.verses', compact('verses'));
    }
}
