<?php

namespace App\Http\Controllers\Bible\Study;

use App\Http\Controllers\APIController;
use App\Models\Bible\Study\Lexicon;
use App\Models\Bible\Study\LexicalDefinition;


class LexiconController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $word = checkParam('word');
        $language = checkParam('langauge');

        return $this->reply(Lexicon::filterByLanguage($language)->when($word, function ($query) use($word) {
            $query->where('def_short',$word);
        })->paginate());
    }

}
