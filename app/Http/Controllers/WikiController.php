<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;

class WikiController extends Controller
{
    public function bibles()
    {
        return view('bibles.index');
    }

    public function bible($id)
    {
        $bible = Bible::where('id', $id)->with('translations', 'books.book', 'links', 'organizations.logo', 'organizations.logoIcon', 'organizations.translations', 'alphabet.primaryFont', 'equivalents', 'filesets')->first();
        return view('bibles.show', compact('bible'));
    }
}
