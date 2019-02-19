<?php

namespace App\Http\Controllers\Bible\Study;

use App\Http\Controllers\APIController;
use App\Models\Bible\Study\Commentary;
use App\Models\Bible\Study\CommentarySection;

class CommentaryController extends APIController
{
    public function index()
    {
        $commentaries = Commentary::with('translations')->get();
        return $this->reply([ 'data' => $commentaries ]);
    }

    public function show($commentary_id)
    {
        $book_id = checkParam('book_id', true);
        $chapter = checkParam('chapter', true);

        $commentary_section = CommentarySection::where('commentary_id',$commentary_id)->where('book_id', $book_id)->where('chapter_start', $chapter)->get();
        return $this->reply([ 'data' => $commentary_section ]);
    }

}
