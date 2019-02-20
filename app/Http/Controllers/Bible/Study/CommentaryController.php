<?php

namespace App\Http\Controllers\Bible\Study;

use App\Http\Controllers\APIController;
use App\Models\Bible\Study\Commentary;
use App\Models\Bible\Study\CommentarySection;

class CommentaryController extends APIController
{

    /**
     * @return mixed
     */
    public function index()
    {
        $commentaries = Commentary::with('translations')->get();
        return $this->reply(['data' => $commentaries]);
    }

    /**
     * @param $commentary_id
     *
     * @return mixed
     */
    public function show($commentary_id)
    {
        $book_id = checkParam('book_id');
        $chapter = checkParam('chapter');

        $commentary = Commentary::with('translations')->get();
        $commentary_sections = CommentarySection::where('commentary_id', $commentary_id)->distinct()
            ->when($book_id, function ($query) use($book_id) {
                $query->where('book_id', $book_id);
            })
            ->when($chapter, function ($query) use($chapter) {
                $query->where('chapter_start', $chapter);
            })
            ->leftJoin('books', function($query) {
                $query->on('books.id','commentary_sections.book_id');
            })->select('book_id','chapter_start')
              ->orderBy('books.protestant_order')
              ->orderBy('commentary_sections.chapter_start')->get();

        return $this->reply(['data' => $commentary_sections, 'meta' => $commentary->toArray()]);
    }

    /**
     * @param $commentary_id
     * @param $book_id
     * @param $chapter
     *
     * @return mixed
     */
    public function sections($commentary_id, $book_id, $chapter) {

        $commentary = Commentary::with('translations')->get();
        $commentary_section = CommentarySection::where([
            ['commentary_id', $commentary_id],
            ['book_id', $book_id],
            ['chapter_start', $chapter]
        ])->get();

        return $this->reply(['data' => $commentary_section, 'meta' => $commentary->toArray()]);
    }

}
