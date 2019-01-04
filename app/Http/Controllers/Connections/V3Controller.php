<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Transformers\BooksTransformer;
use App\Transformers\FileTransformer;
use Spatie\Fractalistic\ArraySerializer;

class V3Controller extends APIController
{
    public function search()
    {
        $bible_id    = checkParam('dam_id');
        $action_type = checkParam('resource', true);

        $fileset       = BibleFileset::where('id', $bible_id)->where('set_type_code', 'text_plain')->firstOrFail();
        if ($action_type === 'books') {
            $booksChapters = BibleVerse::where('hash_id', $fileset->hash_id)->select(['book', 'chapter'])->distinct()->get();
            $books         = $booksChapters->pluck('book')->toArray();
            $chapters      = [];
            foreach ($booksChapters as $books_chapter) {
                $chapters[$books_chapter->book][] = $books_chapter->chapter;
            }
            $books = Book::whereIn('id_usfx', $books)->orderBy('protestant_order')->get()->map(function ($book) use (
                $bible_id,
                $chapters
            ) {
                $book['bible_id']        = $bible_id;
                $book['sophia_chapters'] = $chapters[$book->id_usfx];

                return $book;
            });

            return $this->reply([
                '_links'      => ['self' => ['href' => 'http://v3.dbt.io/search']],
                '_embedded'   => fractal()->collection($books)->serializeWith(new ArraySerializer())->transformWith(new BooksTransformer()),
                'total_items' => $books->count(),
            ]);
        }

        if ($action_type === 'chapters') {
            $files = BibleFile::where('hash_id', $fileset->hash_id)->orWhere('set_id', $bible_id)->get();

            return $this->reply([
                '_links'      => ['self' => ['href' => 'http://v3.dbt.io/search']],
                '_embedded'   => fractal($files, new FileTransformer(), new ArraySerializer()),
                'total_items' => $files->count(),
            ]);
        }
        return null;
    }

    public function books()
    {
        $bible_id = checkParam('dam_id');
        $children = checkParam('children');
        $fileset = BibleFileset::where('id', $bible_id)->firstOrFail();

        $files = $children ? BibleFile::where('hash_id', $fileset->hash_id)->orWhere('set_id', $bible_id)->select([
            'chapter_start as number',
            'chapter_start as order',
            'set_id as dam_id',
            'file_name as audio_path',
            'book_id as book_code',
        ])->get()->groupBy('book_code') : [];
        $booksChapters = BibleVerse::where('hash_id', $fileset->hash_id)->select(['book_id', 'chapter'])->distinct()->get();
        $books = $booksChapters->pluck('book_id')->toArray();
        $books = Book::whereIn('id', $books)->orderBy('protestant_order')->get()->map(function ($book) use ($bible_id, $files) {
            $book['bible_id'] = $bible_id;
            $book['chapters'] = $files[$book->id] ?? [];
            return $book;
        });

        return $this->reply([
            '_links'      => ['self' => ['href' => 'http://v3.dbt.io/search']],
            '_embedded'   => fractal($books, new BooksTransformer(), new ArraySerializer()),
            'total_items' => $books->count(),
        ]);
    }
}
