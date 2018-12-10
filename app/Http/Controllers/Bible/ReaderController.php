<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;

use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\Language\Language;
use App\Models\User\Key;

use App\Traits\AccessControlAPI;

class ReaderController extends APIController
{
    use AccessControlAPI;

    /**
     * The Languages Available View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function languages()
    {
        $languages = \Cache::remember('Bible_is_languages', 2400, function () {
            $project_key = optional(Key::where('name', 'bible.is')->first())->key;
            $access_control = \Cache::remember($project_key.'_access_control', 2400, function () use ($project_key) {
                return $this->accessControl($project_key);
            });
            return Language::select(['languages.id', 'languages.name', 'autonym.name as autonym'])
                ->leftJoin('language_translations as autonym', function ($join) {
                    $join->on('autonym.language_source_id', 'languages.id');
                    $join->on('autonym.language_translation_id', 'languages.id');
                    $join->orderBy('autonym.priority', 'desc');
                })
                ->whereHas('filesets', function ($query) use ($access_control) {
                    $query->whereIn('hash_id', $access_control->hashes);
                    $query->whereHas('fileset', function ($query) {
                        $query->where('set_type_code', 'text_plain')->where('asset_id', 'dbp-prod');
                    });
                })->withCount('bibles')->get();
        });

        return view('bibles.reader.languages', compact('languages'));
    }

    /**
     * @param $language_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bibles($language_id)
    {
        $project_key = Key::where('name', 'bible.is')->first();
        $access_control = \Cache::remember($project_key->key.'_access_control', 2400, function () use ($project_key) {
            return $this->accessControl($project_key->key);
        });

        $filesets = BibleFileset::with('bible.translations')
            ->whereHas('bible', function ($query) use ($language_id) {
                $query->where('language_id', $language_id);
            })
            ->where('asset_id', 'dbp-prod')->where('set_type_code', 'text_plain')->get();

        return view('bibles.reader.bibles', compact('filesets'));
    }

    /**
     *
     * Generates the Book Navigation Menu View for the Bible Fileset
     *
     * @param $bible_id
     *
     * @return \Illuminate\View\View
     */
    public function books($bible_id)
    {
        $fileset = BibleFileset::with('bible')->where('id', $bible_id)->where('asset_id', 'dbp-prod')->where('set_type_code', 'text_plain')->first();
        $language_id = $fileset->bible->first()->language_id;
        $sophia_books = BibleVerse::where('hash_id', $fileset->hash_id)->select('book_id')->distinct()->get();
        $books = Book::whereIn('id', $sophia_books->pluck('book_id')->toArray())->orderBy('protestant_order', 'asc')->get();
        $bible_books = BibleBook::where('bible_id', $bible_id)->whereIn('book_id', $books->pluck('id')->toArray())->get();

        foreach ($books as $book) {
            $currentBook = $bible_books->where('book_id', $book->id)->first();
            $book->vernacular_title = $currentBook ? $currentBook->name : null;
            $book->existing_chapters = $currentBook ? $currentBook->chapters : null;
        }

        return view('bibles.reader.books', compact('books', 'bible_id', 'language_id'));
    }

    /**
     * @param $bible_id
     * @param $book_id
     * @param $chapter
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chapter($bible_id, $book_id, $chapter)
    {
        $fileset = BibleFileset::with('bible')->where('id', $bible_id)->where('asset_id', 'dbp-prod')->where('set_type_code', 'text_plain')->firstOrFail();

        $verses = BibleVerse::where('hash_id', $fileset->hash_id)->where('book_id', $book_id)
            ->where('chapter', $chapter)->orderBy('verse_start')->get();
        return view('bibles.reader.verses', compact('verses'));
    }
}
